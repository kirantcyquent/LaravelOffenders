<?php namespace App\Jobs\Sexoffenders;

use App;
use App\GaOffender;
use App\GaOffense;
use App\GaCounty;
use App\Sexoffender;
use App\SexoffendersStat;
use App\Jobs\Traits\SexoffendersCrawler;
use Carbon\Carbon;
use Goutte\Client;
use App\Library\Decaptcha;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;
use ForceUTF8\Encoding;

class Georgia extends Job implements SelfHandling, ShouldQueue
{
    use SexoffendersCrawler, InteractsWithQueue, SerializesModels;

    protected $page_count = 1;
    protected $curr_county_id;
    protected $curr_county_pages;

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
        $this->profiles_table = false;
        $this->url_base = 'http://state.sor.gbi.ga.gov/Sort_Public/';
        $this->state->status = "Initializing";
        $this->state->save();
        $this->crawler = $this->client->request('GET', $this->url_base);
        if (!$this->check_uri()) {
            return;
        }
        $this->do_conditions();
        $this->changeIncarcerated();
        return;
    }

    private function do_conditions()
    {
        $form_agree = $this->crawler->selectButton('I agree')->form();
        $this->crawler = $this->client->submit($form_agree);
        if (App::runningInConsole()) {
            fwrite(STDOUT, "Conditions Accepted" . PHP_EOL);
        }
        $this->check_uri();
    }

    private function changeIncarcerated()
    {
        $fields = $this->getInputFields();
        $url = $this->url_base . 'SearchOffender.aspx';
        $this->crawler = $this->client->request('POST', $url, $fields);
        $this->check_uri();
        $this->handleCounties();
    }

    private function incarceratedYes()
    {
        $url = $this->url_base . "ConditionsOfUse.aspx";
        $this->crawler = $this->client->request('GET', $url);
        if (!$this->check_uri()) {
            return;
        }
        $fields = $this->getInputFields();
        $fields['ctl00$ContentPlaceHolder1$rbIncarcerated'] = 'Yes';
        $url = $this->url_base . 'SearchOffender.aspx';
        $this->crawler = $this->client->request('POST', $url, $fields);
        $this->check_uri();
        $url = $this->url_base . "SearchOffender.aspx";
        $this->crawler = $this->client->request('GET', $url);
        $this->check_uri();
        $this->changeCounty('All');
    }

    private function handleCounties()
    {
        $url = $this->url_base . 'SearchOffender.aspx';
        $this->crawler = $this->client->request('GET', $url);
        $county = GaCounty::where('status', '=', '0')->orderByRaw("RAND()")->first();
        if ($county) {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "=========== County : " . $county->name . PHP_EOL);
            }
            $this->curr_county_id = $county->county_id;
            $this->curr_county_pages = $county->pages;
            echo $county->county_id . " : " . $county->name . "\n";
            //
            $this->expected_records = $this->curr_county_pages*10;
            $total_counties = GaCounty::count();
            $counties_crawled = GaCounty::where('status','!=', '0')->count();
            $this->state->status = ($counties_crawled+1)."/".$total_counties." - County: " . $county->name;
            $this->estimate_time('counties');
            $this->start_time = time();
            //
            $this->changeCounty($county->county_id);
        } else {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "=========== County DONE" . PHP_EOL);
                fwrite(STDOUT, "=========== Incarcerated : Yes " . PHP_EOL);
            }
            $this->incarceratedYes();
            if (App::runningInConsole()) {
                fwrite(STDOUT, "=========== Incarcerated : Yes ** Done" . PHP_EOL);
            }
            $this->records_crawled = GaOffender::count();
            $this->completed();
        }
    }

    private function changeCounty($county_id)
    {
        $fields = $this->getInputFields();
        $fields['__ASYNCPOST'] = true;
        $fields['__EVENTTARGET'] = '';
        $fields['ctl00$ContentPlaceHolder1$MyScriptManager1'] = 'ctl00$ContentPlaceHolder1$UpdatePanel1|ctl00$ContentPlaceHolder1$btnSearch';
        $fields['ctl00$ContentPlaceHolder1$btnSearch'] = 'Search';
        $fields['ctl00$ContentPlaceHolder1$ddOffense'] = 'All';
        $fields['ctl00$ContentPlaceHolder1$ddRace'] = '0';
        $fields['ctl00$ContentPlaceHolder1$rbGender'] = 'All';
        if ($county_id == 'All') {
            $incarcerated = 'Yes';
        } else {
            $incarcerated = 'All';
        }
        $fields['ctl00$ContentPlaceHolder1$rbIncarcerated'] = $incarcerated;
        $fields['ctl00$ContentPlaceHolder1$txtDistance'] = '';
        $fields['ctl00$ContentPlaceHolder1$txtFirstName'] = '';
        $fields['ctl00$ContentPlaceHolder1$txtLastName'] = '';
        $fields['ctl00$ContentPlaceHolder1$txtStreet'] = '';
        $fields['ctl00$ContentPlaceHolder1$txtZipCode'] = '';

        $fields['ctl00$ContentPlaceHolder1$ddCity'] = 'All';
        $fields['ctl00$ContentPlaceHolder1$ddCounty'] = $county_id;
        $fields['ctl00$ContentPlaceHolder1$ddOffenderType'] = 'All sexual offenders. Incarcerated offenders are excluded when location criteria (i.e. county, address, etc.) is provided.';
        unset($fields['ctl00$ContentPlaceHolder1$btnClear']);
        $url = $this->url_base . 'SearchOffender.aspx';
        $this->crawler = $this->client->request('POST', $url, $fields);
        $this->check_uri();
        $this->startSearch();
    }

    private function startSearch($num_pages = 0)
    {
        if ($num_pages <= 1) {
            $this->searchResults(0);
        } else {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "--- Navigate to :" . $this->$page_count . " ---" . PHP_EOL);
            }
            $pager_num = $this->page_count % 10;
            //
            $fields = $this->getInputFields();
            $fields['__EVENTTARGET'] = 'ctl00$ContentPlaceHolder1$rptPager$ctl10$lnkPage';
            for ($i = 0; $i < $num_pages; $i++) {
                if (App::runningInConsole()) {
                    fwrite(STDOUT, "Pages : " . $i * 10 . " - " . (10 + ($i * 10)) . PHP_EOL);
                }
                $url = $this->url_base . "OffenderSearchResults.aspx";
                $this->crawler = $this->client->request('POST', $url, $fields);
                $fields = $this->getInputFields();
                $fields['__EVENTTARGET'] = 'ctl00$ContentPlaceHolder1$rptPager$ctl11$lnkPage';
                $this->check_uri();
            }
            $fields['__EVENTTARGET'] = 'ctl00$ContentPlaceHolder1$rptPager$ctl' . str_pad($pager_num, 2, "0", STR_PAD_LEFT) . '$lnkPage';
            $this->searchResults($pager_num, $fields);
        }
    }
    private function crawlFrom($page_count) {
        $this->page_count = $page_count;
        $this->client->restart();
        if (App::runningInConsole()) {
            fwrite(STDOUT, "--- Restart from :" . $page_count . " ---" . PHP_EOL);
        }
        $pager_num = $page_count % 10;
        $num_pages = ($page_count - $pager_num) / 10;
        $this->start();
        $this->startSearch($num_pages);
    }
    private function searchResults($page, $fields = array())
    {
        sleep(2);
        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========================== " . PHP_EOL);
            fwrite(STDOUT, "Results: " . $this->page_count . " : " . $page . PHP_EOL);
            fwrite(STDOUT, "=========================== " . PHP_EOL);
        }
        $url = $this->url_base . "OffenderSearchResults.aspx";
        if (!$page) {
            $this->crawler = $this->client->request('GET', $url);
        } else {
            $this->crawler = $this->client->request('POST', $url, $fields);
        }
        $this->check_uri();
        $links = $this->crawler->filter('#ctl00_ContentPlaceHolder1_pnlOffenders a[id*="lnkPage"]')->extract(array('_text', 'href'));
        $pages = array();
        $lastPage = true;
        foreach ($links as $link) {
            $next = false;
            $prev = false;
            $label = $link[0];
            $link = $link[1];
            if (empty($link)) {
                $pages[] = array('target' => 'current', 'prev' => $prev, 'next' => $next, 'label' => $label);
            }else{
                list($j, $target) = explode("'", $link);
                if (strpos($target, 'rptPager') !== false) {
                    if ($label == '[Previous 10]') {
                        $prev = true;
                    }
                    if ($label == '[Next 10]') {
                        $next = true;
                        $lastPage = false;
                    }
                    $pages[] = array('target' => $target, 'prev' => $prev, 'next' => $next, 'label' => $label);
                }
            }
        }
        //var_dump($pages);
        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========== Check links ================ " . PHP_EOL);
        }
        if (empty($pages)) {
            fwrite(STDOUT, "=========== ------ no links ================ " . PHP_EOL);
            $this->crawlFrom($this->page_count);
        } else {
            $real_page_count = $this->crawler->filter('#ctl00_ContentPlaceHolder1_pnlOffenders a[disabled="disabled"]');
            if (count($real_page_count)) {
                $real_page_count = $real_page_count->text();
                $this->page_count = $real_page_count;
            } else {
                $real_page_count = $this->page_count;
            }
        }
        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========== Write files ================ : " . $this->page_count . " /  " . $real_page_count . PHP_EOL);
        }
        // Write the contents back to the file
        // store current page in database or some file
        Storage::put('sexoffenders/_html/'.str_slug($this->state->state_name).'/curr_page_num.html', $real_page_count);

        $fields = $this->getInputHiddenFields();

        for ($i = 0; $i <= 9; $i++) {
            //
            $this->records_crawled = (($this->page_count*10)+($i)-10);
            $this->estimate_time('profiles');
            $this->start_time = time();
            //
            $this->getProfile($fields, $i);
        }
        $this->page_count++;
        $page++;
        $lastPageNum = end($pages);
        //var_dump($lastPageNum);
        $isFinal = false;
        if ($lastPageNum['target'] == "current") {
            $isFinal = true;
        }
        if ($lastPageNum['label'] == "[Previous 10]") {
            $isFinal = true;
        }
        if ($this->page_count > 19) {
            if ($page == 11) {
                $page = 1;
            }
        } else {
            if ($page == 11) {
                $page = 2;
            }
        }
        $fields['__EVENTTARGET'] = 'ctl00$ContentPlaceHolder1$rptPager$ctl' . str_pad($page, 2, "0", STR_PAD_LEFT) . '$lnkPage';
        if (!$lastPage) {
            $this->searchResults($page, $fields);
        } else {
            if ($isFinal) {
                $county = GaCounty::firstOrNew(['county_id' => $this->curr_county_id]);
                $county->status = '1';
                $county->pages = $this->page_count-1;
                $county->save();
                $this->handleCounties();
            } else {
                $this->searchResults($page, $fields);
            }
        }
    }
    private function getProfile($fields = array(), $i = 0) {
        sleep(1);
        $url = $this->url_base . "OffenderSearchResults.aspx";
        $fields['__EVENTTARGET'] = 'ctl00$ContentPlaceHolder1$grdSearchResults';
        $fields['__EVENTARGUMENT'] = 'Select$' . $i;
        $this->crawler = $this->client->request('POST', $url, $fields);

        $this->check_uri();

        //Storage::put('sexoffenders/_html/'.str_slug($this->state->state_name).'/curr_page_profile.html', $this->crawler->html());
        if (App::runningInConsole()) {
            //fwrite(STDOUT, $current_url . PHP_EOL);
        }

        $image = $this->crawler->filter('#ctl00_ContentPlaceHolder1_OffenderDetails1_imgOffender');
        if (count($image)) {
            $image = $this->url_base . $image->attr('src');
        } else {
            fwrite(STDOUT, "=========== ------ no profile $i ================ " . PHP_EOL);
            return;
        }
        $image_data = parse_url($image);
        if (isset($image_data['query']) || array_key_exists('query', $image_data)) {
            parse_str($image_data['query']);
        }
        if (empty($OffenderId)) {
            return;
        }
        if (App::runningInConsole()) {
            fwrite(STDOUT, "Profile: ");
        }
        $id = "GA" . $OffenderId;
        $offenderid = $OffenderId;

        if (App::runningInConsole()) {
            fwrite(STDOUT, $id . PHP_EOL);
        }
        $image = $this->crawler->filter('#ctl00_ContentPlaceHolder1_OffenderDetails1_imgOffender')->extract('src');
        $image = $this->url_base . $image[0];
        $ext = $this->getImageExt($image);
        $temp_img = 'sexoffenders/'.snake_case($this->state->state_name).'/' . $offenderid . $ext;
        if($ext != '.nofile'){
            Storage::put($temp_img, file_get_contents($image));
        }else{
            Storage::put($temp_img, '');
        }
        $this->process_image($temp_img, $ext);

        $siteinfo = array();
        $this->crawler->filter('#offenderinfo tr')->each(function ($node, $i) use (&$siteinfo){
            //echo $node->text().PHP_EOL;
            $data = $this->cleanup_text($node->text());
            $field = $this->cleanup_text($node->filter('td')->last()->text());
            if (strpos($data, 'First Name') !== FALSE) {
                $siteinfo['f_name'] = $field;
            }
            if (strpos($data, 'Middle Name') !== FALSE) {
                $siteinfo['m_name'] = $field;
            }
            if (strpos($data, 'Last Name') !== FALSE) {
                $siteinfo['l_name'] = $field;
            }
            if (strpos($data, 'Suffix') !== FALSE) {
                $siteinfo['s_name'] = $field;
            }
            if (strpos($data, 'Gender:') !== FALSE) {
                $siteinfo['gender'] = $field;
            }
            if (strpos($data, 'Race') !== FALSE) {
                $siteinfo['race'] = $field;
            }
            if (strpos($data, 'Year') !== FALSE) {
                $siteinfo['year'] = $field;
            }
            if (strpos($data, 'Height') !== FALSE) {
                $height = str_replace(array(' ', 'FT', 'IN'), array("", "'", '"'), $field);
                $siteinfo['height'] = $height;
            }
            if (strpos($data, 'Weight') !== FALSE) {
                $siteinfo['weight'] = str_replace(array(" ", "LBS"), "", $field);
            }
            if (strpos($data, 'Hair') !== FALSE) {
                $siteinfo['hair'] = $field;
            }
            if (strpos($data, 'Eye') !== FALSE) {
                $siteinfo['eyes'] = $field;
            }
        });

        $info = $this->crawler->filter('#offenderinfo')->html();
        list($dummy, $alias) = explode("Aliases:", $info);
        list($alias, $dummy) = explode("Gender", $alias);
        $temp_crawler = new Crawler();
        $temp_crawler->addHtmlContent($alias);
        $alias = $temp_crawler->filter('div')->each(function ($node, $i) {
            return $this->cleanup_text($node->text());
        });
        list($dummy, $address) = explode("Known Address:", $info);
        list($address, $dummy) = explode("Physical ", $address);
        $temp_crawler = new Crawler();
        $temp_crawler->addHtmlContent($address);
        $address = $temp_crawler->filter('div')->each(function ($node, $i) {
            return $this->cleanup_text($node->text());
        });

        $siteinfo['alias'] = implode(';', $alias);
        $city = "";
        $state = "";
        $zip = "";
        $county = "";
        @list($street, $city, $county) = $address;
        $siteinfo['street'] = $street;
        if (!empty($city)) {
            list($city, $state) = explode(",", $city);
            list($state, $zip) = explode(" ", trim($state));
        }
        $siteinfo['city'] = trim($city);
        $siteinfo['state'] = trim($state);
        $siteinfo['zip'] = trim($zip);
        $siteinfo['county'] = trim(str_replace("County:", "", $county));
        $siteinfo['url'] = "http://state.sor.gbi.ga.gov/Sort_Public/";
        if (isset($address[2])) {
            unset($address[2]);
        }
        $siteinfo['address'] = implode(', ', $address);
        $offences = $this->crawler->filter('#ctl00_ContentPlaceHolder1_OffenderDetails1_offenses tr')->each(function( $node, $i) {
            $date = $node->filter('td')->first()->extract('_text');
            $offence = $node->filter('td')->last()->extract('_text');
            if (!empty($offence[0])) {
                return array('date' => $this->cleanup_text($date[0]), 'offence' => $this->cleanup_text($offence[0]));
            }
        });
        $offences = array_filter($offences);
        extract($siteinfo);
        //dd($siteinfo);
        //echo 'Year: '.$year.PHP_EOL;
        $dob = Carbon::create($year, 1, 1, 12, 0, 0);;
        $year = $dob->toDateString();
        $age = $dob->age;
        $name = $f_name . " " . $m_name . " " . $l_name . " " . $s_name;

        $offender = GaOffender::firstOrNew(['so_offenderid' => $offenderid]);
        $offender->so_id = $id;
        $offender->so_offenderid = $offenderid;
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
        $offender->so_url = $url;
        $offender->save();

        $i = 0;
        foreach ($offences as $offence) {
            extract($offence);
            $of_hash = md5($id . $offence . $i);
            $offense =GaOffense::firstOrNew(['hash' => $of_hash]);
            $offense->of_offendersid = $id;
            $offense->of_Offense = $offence;
            $offense->of_date = $date;
            $offense->hash = $of_hash;
            $offense->save();
            $i++;
        }
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
            echo 'HTTP not 200';
            $action = false;
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
        $temp_img = 'so_captcha_' . str_slug($this->state->state_name).'_'.uniqid() . '.jpg';
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
    private function getInputHiddenFields()
    {
        $fields = [];
        $this->crawler->filter('input[type="hidden"]')->each(function ($input, $i) use (&$fields) {
            $name = $input->attr('name');
            $value = $input->attr('value');
            $fields[$name] = $value;
        });
        return $fields;
    }
    private function getInputFields()
    {
        $fields = [];
        $this->crawler->filter('input')->each(function ($input, $i) use (&$fields) {
            $name = $input->attr('name');
            $value = $input->attr('value');
            $fields[$name] = $value;
        });
        $fields['__ASYNCPOST'] = 'true';
        $fields['ctl00$ContentPlaceHolder1$MyScriptManager1'] = 'ctl00$ContentPlaceHolder1$UpdatePanel1|ctl00$ContentPlaceHolder1$rbIncarcerated$2';
        $fields['ctl00$ContentPlaceHolder1$ddCity'] = 'All';
        $fields['ctl00$ContentPlaceHolder1$ddCounty'] = 'All';
        $fields['ctl00$ContentPlaceHolder1$ddOffenderType'] = 'All sexual offenders. Incarcerated offenders are excluded when location criteria (i.e. county, address, etc.) is provided.';
        unset($fields['ctl00$ContentPlaceHolder1$btnClear']);
        return $fields;
    }

    private function estimate_time($type)
    {
        // clear history
        $history = $this->client->getHistory();
        $history->clear();
        //
        $remaining_records=0;
        if(empty($this->start_time)){
            $this->start_time = time();
        }
        switch ($type) {
            case 'counties':
                $remaining_records = $this->curr_county_pages;
                break;
            case 'profiles':
                $this->state->records_crawled = $this->records_crawled;
                if ($this->records_crawled) {
                    $remaining_records = ($this->expected_records - $this->records_crawled);
                }
                break;
        }
        $record_time = time() - $this->start_time;
        $expected_time = round($record_time * $remaining_records);
        $this->state->records_expected = $this->expected_records;
        $this->state->expected_time = Carbon::now()->addSeconds($expected_time);
        $this->state->save();
        // Check Crawl State
        $this->check_state();
    }

    private function cleanup_text($text)
    {
        $text = trim(preg_replace('/(\s\s+|\t|\n)/',' ', $text));
        return Encoding::fixUTF8($text);
    }
}
