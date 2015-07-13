<?php namespace App\Jobs\Sexoffenders;

use App;
use App\KsOffender;
use App\KsOffense;
use App\KsProfile;
use App\KsCounty;
use App\Sexoffender;
use Carbon\Carbon;
use App\Jobs\Traits\SexoffendersCrawler;
use App\Library\Decaptcha;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

class Kansas extends Job implements SelfHandling, ShouldQueue
{
    use SexoffendersCrawler, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Sexoffender $state)
    {
        $this->state = $state;
    }

    private function start()
    {
        $this->url_base = 'http://www.kbi.ks.gov/registeredoffender/';
        $this->state->status = "Initializing";
        $this->state->save();
        $this->crawler = $this->client->request('GET', $this->url_base);
        if (!$this->check_uri()) {
            return;
        }
        $this->do_conditions();
        $this->geographical_search();
        return;
    }

    private function do_conditions()
    {
        $fields = $this->getInputFields();
        $fields['ctl00$ContentPlaceHolder1$btnAgree'] = 'I agree';
        $url = $this->url_base . 'ConditionsOfUse.Aspx';
        $this->crawler = $this->client->request('POST', $url, $fields);
        if (App::runningInConsole()) {
            fwrite(STDOUT, "Conditions Accepted" . PHP_EOL);
        }
        $this->check_uri();
    }

    private function geographical_search()
    {
        $this->change_offender_type();
        $county = KsCounty::where('status', '=', '0')->orderByRaw("RAND()")->first();

        if ($county) {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "=========== County : " . $county->name . PHP_EOL);
            }
            $curr_county_id = $county->county_id;
            echo $curr_county_id . "\n";
            $this->get_results($curr_county_id);
        } else {
            if (App::runningInConsole()) {
                $this->state->status = "Extracting Profiles";
                $this->state->save();
                fwrite(STDOUT, "=========== County DONE" . PHP_EOL);
                $this->extract_profiles();
            }
        }
    }

    private function change_offender_type()
    {

        sleep(1);
        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========== Change Offender Type" . PHP_EOL);
        }
        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========== Remove 1" . PHP_EOL);
        }
        $url = $this->url_base . "GeographicalSearch.aspx";
        $this->crawler = $this->client->request('GET', $url);
        if (!$this->check_uri()) {
            $this->change_offender_type();
        }
        $form = $this->crawler->selectButton('Search')->form();
        $fields = $form->getValues();
        $fields['__EVENTTARGET'] = 'ctl00$ContentPlaceHolder1$chkOffenderType$1';
        unset($fields['ctl00$ContentPlaceHolder1$chkOffenderType$1']);
        //var_dump($fields);
        try {
            $this->crawler = $this->client->request('POST', $url, $fields);
        } catch (\GuzzleHttp\Ring\Exception\ConnectException $e) {
            sleep(10);
            $this->geographical_search();
        }
        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========== Remove 2" . PHP_EOL);
        }

        $url = $this->url_base . "GeographicalSearch.aspx";
        $this->crawler = $this->client->request('GET', $url);
        if (!$this->check_uri()) {
            $this->change_offender_type();
        }
        $form = $this->crawler->selectButton('Search')->form();
        $fields = $form->getValues();
        $fields['__EVENTTARGET'] = 'ctl00$ContentPlaceHolder1$chkOffenderType$2';
        unset($fields['ctl00$ContentPlaceHolder1$chkOffenderType$2']);
        //var_dump($fields);
        try {
            $this->crawler = $this->client->request('POST', $url, $fields);
        } catch (\GuzzleHttp\Ring\Exception\ConnectException $e) {
            sleep(10);
            $this->geographical_search();
        }
    }

    private function get_results($county)
    {
        sleep(2);
        $this->check_uri();
        if ($county == '0') {
            return;
        }
        $current_url = $this->client->getRequest()->getUri();
        if (strtolower($current_url) != strtolower($this->url_base . "GeographicalSearch.aspx")) {
            $url = $this->url_base . "GeographicalSearch.aspx";
            $this->crawler = $this->client->request('GET', $url);
            if (!$this->check_uri()) {
                $url = $this->url_base . "GeographicalSearch.aspx";
                $this->crawler = $this->client->request('GET', $url);
            }
        }

        $form = $this->crawler->selectButton('Search')->form();
        $fields = $form->getValues();

        $fields['ctl00$ContentPlaceHolder1$rdoSearchType'] = "rdoCounty";
        $fields['ctl00$ContentPlaceHolder1$txtCounty'] = $county;

        try {
            $this->state->status = "County: " . $county;
            $this->estimate_time('counties');
            $this->start_time = time();
            $crawler_search = $this->client->submit($form, $fields);
            if (!$this->check_uri()) {
                $this->geographical_search();
            }
            Storage::put('sexoffenders/_html/' . str_slug($this->state->state_name) . '/' . $county . '.html', $crawler_search->html());
            if (App::runningInConsole()) {
                fwrite(STDOUT, $county . " : + Links Updated" . PHP_EOL);
            }
            $status = 1;
        } catch (\GuzzleHttp\Ring\Exception\ConnectException $e) {
            if (App::runningInConsole()) {
                fwrite(STDOUT, $county . " : + Server too long to respond / connection reset" . PHP_EOL);
            }
            $status = 2;
        }

        $county = KsCounty::firstOrNew(['county_id' => $county]);
        $county->status = $status;
        $county->save();
        //
        unset($form);
        unset($crawler_search);
        unset($county);
        //
        $this->geographical_search();
    }

    private function extract_profiles()
    {
        $county = KsCounty::where('status', '=', '1')->orderBy('county_id')->first();
        if ($county) {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "===========  Extract Profiles : " . $county->name . PHP_EOL);
            }
            $curr_county_id = $county->county_id;
            echo $curr_county_id . "\n";
            $html = Storage::get('sexoffenders/_html/' . str_slug($this->state->state_name) . '/' . $curr_county_id . '.html');
            $link_crawler = new Crawler($html);
            try {
                $table = $link_crawler->filter('#ctl00_ContentPlaceHolder1_OffenderSearchListView_divGeocodeTrue')->nextAll();
                $links = $table->filter('a')->extract(array('href'));
                foreach ($links as $link) {
                    $url = str_replace("./", $this->url_base, $link);
                    $hash = md5($url);

                    $profile = KsProfile::firstOrNew(array('hash' => $hash));
                    $profile->url = $url;
                    $profile->hash = $hash;
                    $profile->county_id = $curr_county_id;
                    $profile->save();
                    unset($profile);
                }
                $status = 3;
            } catch (\InvalidArgumentException $e) {
                $status = 4;
            }
            $county = KsCounty::firstOrNew(['county_id' => $curr_county_id]);
            $county->status = $status;
            $county->save();

            $this->state->records_expected = KsProfile::count();
            $this->state->save();
            //
            unset($html);
            unset($link_crawler);
            unset($county);
            //
            $this->extract_profiles();
        } else {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "=========== Extract DONE" . PHP_EOL);
            }
            $this->process_profiles();
        }
    }

    private function process_profiles()
    {
        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========== Profiles" . PHP_EOL);
        }
        $profile = KsProfile::where('status', '=', '0')->orderByRaw("RAND()")->first();
        // profile time calcs
        $this->estimate_time('profiles');
        //
        $this->start_time = time();
        if ($profile) {
            $url = $profile->url;
            $county = $profile->county_id;
            $county = KsCounty::where('county_id', '=', $county)->first();
            //
            $this->extract_data($url, $county->name);
        } else {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "=========== Profiles Done" . PHP_EOL);
                $this->completed();
            }
        }
    }

    private function extract_data($url, $county_name)
    {
        //$Id = $url;
        //$url = 'http://laravel/kansas/index.html?Display=Main&Id=18313';

        $this->crawler = $this->client->request('GET', $url);

        if (!$this->check_uri()) {
            $this->process_profiles();
        }
        $url_data = parse_url($url);
        parse_str($url_data['query']);
        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========== Profile : " . $Id . PHP_EOL);
            //fwrite(STDOUT, $url.PHP_EOL);
        }

        $this->state->status = "Profile : " . $Id;
        $this->state->save();

        $results = $this->crawler->filter('#ctl00_ContentPlaceHolder1_divButtons')->count();
        if (empty($results)) {
            $profile = KsProfile::firstOrNew(['url' => $url]);
            $profile->status = '2';
            $profile->save();
            unset($profile);
            $this->process_profiles();
        }
        $siteinfo = [];
        $this->crawler->filter('div.Row')->each(function ($node, $i) use (&$siteinfo) {
            $data = $this->cleanup_text($node->text());
            if (strpos($data, 'First Name') !== FALSE) {
                $siteinfo['f_name'] = trim(str_replace("First Name:", "", $data));
            }
            if (strpos($data, 'Middle Name') !== FALSE) {
                $siteinfo['m_name'] = trim(str_replace("Middle Name:", "", $data));
            }
            if (strpos($data, 'Last Name') !== FALSE) {
                $siteinfo['l_name'] = trim(str_replace("Last Name:", "", $data));
            }
            if (strpos($data, 'Name Suffix') !== FALSE) {
                $siteinfo['s_name'] = trim(str_replace("Name Suffix:", "", $data));
            }
            if (strpos($data, 'Gender') !== FALSE) {
                $siteinfo['gender'] = trim(str_replace("Gender:", "", $data));
            }
            if (strpos($data, 'Race') !== FALSE) {
                $siteinfo['race'] = trim(str_replace("Race:", "", $data));
            }
            if (strpos($data, 'Ethnicity') !== FALSE) {
                $siteinfo['ethnicity'] = trim(str_replace("Ethnicity:", "", $data));
            }
            if (strpos($data, 'County') !== FALSE) {
                $siteinfo['county'] = trim(str_replace("County:", "", $data));
            }
            if (strpos($data, 'Height') !== FALSE) {
                $siteinfo['height'] = str_replace(array(' ', 'FT', 'IN'), array("", "'", '"'), str_replace("Height:", "", $data));
            }
            if (strpos($data, 'Weight') !== FALSE) {
                $siteinfo['weight'] = str_replace(array(" ", "LBS"), "", str_replace("Weight:", "", $data));
            }
            if (strpos($data, 'Hair Color') !== FALSE) {
                $siteinfo['hair'] = trim(str_replace("Hair Color:", "", $data));
            }
            if (strpos($data, 'Eye Color') !== FALSE) {
                $siteinfo['eyes'] = trim(str_replace("Eye Color:", "", $data));
            }
            if (strpos($data, 'Date of Birth') !== FALSE) {
                $siteinfo['year'] = trim(str_replace("Date of Birth:", "", $data));
            }
        });

        extract($siteinfo);
        $address = "";
        $alias = "";
        $street = "";
        $city = "";
        $state = "";
        $zip = "";
        $age = "";
        $latitude = "";
        $longitude = "";
        $so_id = "KS" . $Id;
        $so_offenderid = $Id;
        $dob = Carbon::parse($year);
        $year = $dob->toDateString();
        $age = $dob->age;
        $name = $f_name . " " . $m_name . " " . $l_name . " " . $s_name;
        echo $name . PHP_EOL;
        $so_url = $url;

        $aliases_html = $this->crawler->filter('div.header')->eq(1)->parents()->html();
        $alias_dom = new Crawler($aliases_html);
        $aliases = [];

        $alias_dom->filter('div')->each(function ($node, $i) use (&$aliases) {
            $alias = $this->cleanup_text($node->text());
            if ((strpos($alias, 'Alias') === FALSE) && !empty($alias)) {
                $aliases[] = $alias;
            }
        });
        $alias = implode("; ", $aliases);

        $address_html = $this->crawler->filter('div.header')->eq(3)->parents()->html();
        $address_dom = new Crawler($address_html);
        $address = [];
        $address_dom->filter('div')->each(function ($node, $i) use (&$address) {
            $address[] = $this->cleanup_text($node->text());
        });

        if (strpos($address[2], 'Registration') === FALSE) {
            list($city, $zip) = explode(',', $address[2]);
            $street = $address[1];

            $address = $address[1] . ', ' . $address[2];
        } else {
            $street = '';
            $city = '';
            $zip = '';
            if (strpos($address[1], 'Registration') === FALSE) {
                $address = $address[1];
            } else {
                $address = '';
            }
        }

        $zip = trim(str_replace('KS', '', $zip));
        $state = $this->state->state_name;
        $got_it_from = $county_name;

        $offences = $this->crawler->filter('#ctl00_ContentPlaceHolder1_OffenderDetails1_offenses tr')->each(function ($node, $i) {
            $date = $node->filter('td')->eq(1)->extract('_text');
            $offence = $node->filter('td')->eq(5)->extract('_text');
            if (!empty($offence[0])) {
                return array('date' => $date[0], 'offence' => $offence[0]);
            }
        });
        $offences = array_filter($offences);

        $address_url = str_replace('=Main', '=OtherAddresses', $url);
        $this->crawler = $this->client->request('GET', $address_url);
        if (!$this->check_uri()) {
            $this->process_profiles();
        }
        try {
            $addressJson = $this->crawler->filter('#ctl00_ContentPlaceHolder1_OffenderDetails1_AddressMap_ClientState')->attr('value');
            $json = json_decode($addressJson);
            $json = $json[0];
            $latitude = $json->Latitude;
            $longitude = $json->Longitude;
            unset($json);
            unset($addressJson);
        } catch (\InvalidArgumentException $e) {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "- No Lat Long" . PHP_EOL);
            }
        }

        $offender = KsOffender::firstOrNew(['so_offenderid' => $so_offenderid]);
        $offender->so_id = $so_id;
        $offender->so_offenderid = $so_offenderid;
        $offender->so_name = $name;
        $offender->so_alias = $alias;
        $offender->so_address = $address;
        $offender->so_street = $street;
        $offender->so_city = $city;
        $offender->so_state = $state;
        $offender->zip = $zip;
        $offender->so_race = $race;
        $offender->so_sex = $gender;
        $offender->so_height = $height;
        $offender->so_weight = $weight;
        $offender->so_eyes = $eyes;
        $offender->so_hair = $hair;
        $offender->so_dob = $year;
        $offender->so_age = $age;
        $offender->so_url = $so_url;
        $offender->latitude = $latitude;
        $offender->longitude = $longitude;
        $offender->got_it_from = $got_it_from;
        $offender->save();

        $i = 0;
        foreach ($offences as $offence) {
            extract($offence);
            $of_hash = md5($so_id . $offence . $i);
            $offense = KsOffense::firstOrNew(['hash' => $of_hash]);
            $offense->of_offendersid = $so_id;
            $offense->of_Offense = $offence;
            $offense->of_date = $date;
            $offense->hash = $of_hash;
            $offense->save();
            unset($offense);
            $i++;
        }

        $image = $this->crawler->filter('#ctl00_ContentPlaceHolder1_OffenderDetails1_imgOffender')->extract('src');
        $image = $this->url_base . $image[0];
        $ext = $this->getImageExt($image);
        $temp_img = 'sexoffenders/' . snake_case($this->state->state_name) . '/' . $Id . $ext;
        if ($ext != '.nofile') {
            Storage::put($temp_img, file_get_contents($image));
        } else {
            Storage::put($temp_img, '');
        }
        $this->process_image($temp_img, $ext);

        $profile = KsProfile::firstOrNew(['url' => $url]);
        $profile->status = '1';
        $profile->save();
        // Free Memory
        $this->crawler = '';
        unset($aliases_html);
        unset($alias_dom);
        unset($address_dom);
        unset($address_html);
        unset($siteinfo);
        unset($offender);
        unset($offences);
        unset($ext);
        unset($image);
        unset($temp_img);
        unset($profile);
        $this->process_profiles();
    }

    private function check_uri()
    {
        $action = true;
        $status_code = $this->client->getResponse()->getStatus();
        $current_url = $this->client->getRequest()->getUri();
        if ($this->debug && App::runningInConsole()) {
            fwrite(STDOUT, $status_code . " : " . $current_url . PHP_EOL);
        }
        if (strtolower($current_url) == strtolower($this->url_base . "Captcha.aspx")) {
            $this->captcha_submit();
            $action = false;
        }
        if (strtolower($current_url) == strtolower($this->url_base . "ConditionsOfUse.aspx")) {
            $this->do_conditions();
            $action = false;
        }
        if ($status_code != "200") {
            return false;
        }
        return $action;
    }

    private function captcha_submit()
    {

        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========================== " . PHP_EOL);
            fwrite(STDOUT, "Submit Captcha" . PHP_EOL);
            fwrite(STDOUT, "=========================== " . PHP_EOL);
        }
        try {
            $form_captcha = $this->crawler->selectButton('Continue')->form();
        } catch (\InvalidArgumentException $e) {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "Revisit Captcha *" . PHP_EOL);
                fwrite(STDOUT, "=========================== " . PHP_EOL);
            }
            $url = $this->url_base . "Captcha.aspx";
            $this->crawler = $this->client->request('GET', $url);
            $form_captcha = $this->crawler->selectButton('Continue')->form();
        }
        $image = $this->url_base . $this->crawler->filter('#LBD_CaptchaImage img')->attr('src');
        $this->client->request('GET', $image);
        $image = $this->client->getResponse()->getContent();
        if (App::runningInConsole()) {
            fwrite(STDOUT, "Captcha *" . PHP_EOL);
        }
        $temp_img = 'so_captcha_' . str_slug($this->state->state_name) . '_' . uniqid() . '.jpg';
        Storage::put($temp_img, $image);
        $temp_img = storage_path() . '/app/' . $temp_img;
        $decaptcha = new Decaptcha($temp_img);
        $captcha_text = $decaptcha->process();
        if ($this->debug) {
            echo "C: " . $captcha_text . PHP_EOL;
            if (App::runningInConsole()) {
                fwrite(STDOUT, "Captcha Solved" . PHP_EOL);
            }
        }
        unlink($temp_img);
        $this->crawler = $this->client->submit($form_captcha, array('ctl00$ContentPlaceHolder1$CodeTextBox' => $captcha_text));
        if (App::runningInConsole()) {
            fwrite(STDOUT, "Captcha Submitted" . PHP_EOL);
        }
        unset($decaptcha);
        unset($image);
        unset($form_captcha);
        $this->check_uri();
    }

    private function getInputFields()
    {
        $fields = [];
        $this->crawler->filter('input[type="hidden"]')->each(function ($input, $i) use (&$fields) {
            $name = $input->attr('name');
            $value = $input->attr('value');
            $fields[$name] = $value;
        });
        return $fields;
    }

    private function estimate_time($type)
    {
        // clear history
        $history = $this->client->getHistory();
        $history->clear();
        unset($history);
        //
        $remaining_records = 0;
        if (empty($this->start_time)) {
            $this->start_time = time();
        }
        if (empty($this->expected_records)) {
            $this->expected_records = KsProfile::count();
        }
        switch ($type) {
            case 'counties':
                $total_counties = KsCounty::count();
                $counties_crawled = KsCounty::where('status', '!=', '0')->count();
                $remaining_records = ($total_counties - $counties_crawled);
                break;
            case 'profiles':
                $this->records_crawled = KsProfile::where('status', '=', '1')->count();
                $this->state->records_crawled = $this->records_crawled;
                if ($this->records_crawled) {
                    $remaining_records = ($this->expected_records - $this->records_crawled);
                }
                break;
        }
        $record_time = time() - $this->start_time;
        $expected_time = round($record_time * $remaining_records);
        $this->state->expected_time = Carbon::now()->addSeconds($expected_time);
        $this->state->save();
        // Check Crawl State
        $this->check_state();
    }
}
