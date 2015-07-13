<?php namespace App\Jobs\Sexoffenders;

use App;
use App\DeOffender;
use App\DeOffense;
use App\Jobs\Traits\SexoffendersCrawler;
use App\Sexoffender;
use App\DeProfile;
use Carbon\Carbon;
use App\Library\Decaptcha;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class Delaware extends Job implements SelfHandling, ShouldQueue
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
        $this->counties_table = false;
        $this->url_base = 'https://sexoffender.dsp.delaware.gov/';
        $this->counties = array("K" => "Kent",
            "N" => "New Castle",
            "O" => "Other",
            "S" => "Sussex",
            "U" => "Unknown");
        $this->state->status = "Initializing";
        $this->state->save();
        if ($this->state->crawl_state != 'paused') {
            $this->crawler = $this->client->request('GET', $this->url_base);
            $this->token = $this->crawler->filter('input[name="__RequestVerificationToken"]')->attr('value');
            $this->recaptcha_submit();
            $this->process_counties();
        } else {
            $this->process_profiles();
        }
        return;
    }

    private function process_counties()
    {
        foreach ($this->counties as $county_name => $county) {
            $this->search_results($county_name, $county);
            $this->records_expected();
        }
        $this->process_profiles();
        return;
    }

    private function records_expected()
    {
        $this->expected_records = DeProfile::count();
        $this->state->records_expected = $this->expected_records;
        $this->state->save();
    }

    private function search_results($county_name, $county)
    {
        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========================== " . $county . ":" . $county_name . PHP_EOL);
        }
        $fields = array();
        $fields['ConvictionState'] = "*ALL";
        $fields['County'] = $county_name;
        $fields['Development'] = "";
        $fields['ExcludeInJail'] = "false";
        $fields['FirstName'] = "";
        $fields['HouseNumber'] = "";
        $fields['IncludeHomeless'] = "false";
        $fields['IncludeWanted'] = "false";
        $fields['LastName'] = "";
        $fields['OnlineId'] = "";
        $fields['PageSize'] = "8";
        $fields['SearchType'] = "offender";
        $fields['StreetName'] = "";
        $fields['Workplace'] = "";
        $fields['XLongitudeMax'] = "";
        $fields['XLongitudeMin'] = "";
        $fields['YLatitudeMax'] = "";
        $fields['YLatitudeMin'] = "";
        $fields['__RequestVerificationToken'] = $this->token;
        $url = $this->url_base . 'Search';
        $this->crawler = $this->client->request('POST', $url, $fields);
        $json = $this->client->getResponse()->getContent();
        //echo $json.PHP_EOL;
        $data = json_decode($json);
        //dump($data);
        if (!$data->success) {
            echo "SAy What" . PHP_EOL;
            if ($this->debug) {
                var_dump($data);
            }
            $this->recaptcha_submit();
            $this->search_results($county_name, $county);
        } else {
            $ids = $data->ids;
            foreach ($ids as $id) {
                $url = $this->url_base . "?/Detail/" . $id;
                $hash = md5($url);
                $profile = DeProfile::firstOrNew(array('hash' => $hash));
                $profile->url = $url;
                $profile->hash = $hash;
                $profile->county_id = $county_name;
                $profile->save();
            }
        }
        return;
    }
    private function process_profiles()
    {
        $this->records_crawled = DeProfile::where('status', '=', '1')->count();
        $this->state->records_crawled = $this->records_crawled;
        $this->state->save();
        $this->estimate_time();
        $this->start_time = time();
        $profile = DeProfile::where('status', '=', '0')->orderByRaw("RAND()")->first();
        //var_dump($profile);
        if ($profile) {
            $url = $profile->url;
            $id = explode("/", $url);
            $id = end($id);
            $county = $profile->county_id;
            $county_name = $this->counties[$county];
            $this->extract_data($id, $county_name, $profile->hash);
        } else {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "=========== Profiles Done Restart" . PHP_EOL);
            }
            $this->completed();
            return;
        }
    }

    private function extract_data($id, $county_name, $hash)
    {

        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========== " . $county_name . " : " . $id . PHP_EOL);
        }
        $fields = array();
        $fields['__RequestVerificationToken'] = $this->token;
        $fields['id'] = $id;
        $url = $this->url_base . 'GetOffenderDetails';
        $this->crawler = $this->client->request('POST', $url, $fields);
        $json = $this->client->getResponse()->getContent();
        $data = json_decode($json);
        if ($this->debug) {
            var_dump($data);
        }
        if (!$data->success) {
            if (!empty($data->needsCaptcha) && $data->needsCaptcha) {
                if (App::runningInConsole()) {
                    fwrite(STDOUT, "Captcha init *" . PHP_EOL);
                }
                $this->recaptcha_submit();
                $this->process_profiles();
            } else {
                $profile = DeProfile::firstOrNew(['hash' => $hash]);
                $profile->status = '2';
                $profile->save();
                $this->process_profiles();
            }
        } else {
            $data = $data->offender;
            $so_id = "DE" . $id;
            $so_offenderid = $id;
            $name = explode(", ", $data->Name->FriendlyName);
            $name = trim($name[1] . " " . $name[0]);
            $so_targets = ($data->RiskLevel == 2) ? 'Tier 2 (Moderate Risk)' : 'Tier 3 (High Risk)';

            $race = $data->Race;
            $gender = $data->Gender;

            $height = intval($data->Height / 12) . "' " . ($data->Height % 12) . '"';
            $weight = $data->Weight;
            $eyes = $data->EyeColor;
            $hair = $data->HairColor;
            $aliases = array();
            foreach ($data->Aliases as $alias) {
                $name = explode(", ", $alias->FriendlyName);
                $name = trim($name[1] . " " . $name[0]);
                $aliases[] = $name;
            }
            $alias = implode('; ', $aliases);
            $street = $data->HomeAddress->StreetNumber . " " . $data->HomeAddress->StreetPrefix . " " . $data->HomeAddress->StreetName . " " . $data->HomeAddress->StreetType . " " . $data->HomeAddress->AddressLine2;
            $city = $data->HomeAddress->City;
            $state = $data->HomeAddress->State;
            $zip = $data->HomeAddress->Zip;

            $address = $street . " " . $city . " " . $state . " " . $zip;
            $so_url = $this->url_base . "?/Detail/" . $id;
            $latitude = "";
            $longitude = "";
            $got_it_from = "";
            $dob = Carbon::parse($data->DateOfBirth);
            $year = $dob->toDateString();
            $age = $dob->age;

            $offender = DeOffender::firstOrNew(['so_offenderid' => $so_offenderid]);
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
            $offender->so_targets = $so_targets;
            $offender->latitude = $latitude;
            $offender->longitude = $longitude;
            $offender->got_it_from = $got_it_from;
            $offender->save();

            $i = 0;
            $offences = array();
            foreach ($data->Arrests as $offence) {
                $date = Carbon::parse($offence->AdjudicationDate)->toDateString();
                $of_offence = $offence->Description;
                $of_hash = md5($so_id . $of_offence . $i);


                $offense = DeOffense::firstOrNew(['hash' => $of_hash]);
                $offense->of_offendersid = $so_id;
                $offense->of_Offense = $of_offence;
                $offense->of_date = $date;
                $offense->hash = $of_hash;
                $offense->save();

                $i++;
            }
            $image = $this->url_base . '/Image/Full/' . $id;
            $ext = $this->getImageExt($image);
            $temp_img = 'sexoffenders/' . snake_case($this->state->state_name) . '/' . $id . $ext;
            Storage::put($temp_img, file_get_contents($image));
            $this->process_image($temp_img, $ext);

            $profile = DeProfile::firstOrNew(['hash' => $hash]);
            $profile->status = '1';
            $profile->save();
            $this->process_profiles();
        }
    }

    private function recaptcha_submit()
    {
        if (App::runningInConsole()) {
            fwrite(STDOUT, "=========================== " . PHP_EOL);
            fwrite(STDOUT, "Captcha:" . PHP_EOL);
            fwrite(STDOUT, "=========================== " . PHP_EOL);
        }
        //
        $recaptcha_pk = '6Lco4fASAAAAAN0vsQR96BOKL3zbqb1eN0-5OhUr';
        $recaptchaUrl = "https://www.google.com/recaptcha/api/challenge?k=".$recaptcha_pk."&ajax=1&cachestop=0.".rand(111,999).'5810447420609';
        //
        echo PHP_EOL."URL: ".$recaptchaUrl.PHP_EOL;
        $this->client->request('GET', $recaptchaUrl);
        $data = $this->client->getResponse()->getContent();
        print($data.PHP_EOL);
        preg_match("/challenge\s*:\s*'(.+?)'/is", $data, $challenge);
        $challenge = $challenge[1];
        dump($challenge);
        //
        $recaptchaUrl = 'http://www.google.com/recaptcha/api/reload?c=' . $challenge . '&k='.$recaptcha_pk.'&reason=i&type=image&lang=en-GB&th=';
        //
        echo PHP_EOL."URL: ".$recaptchaUrl.PHP_EOL;
        $this->client->request('GET', $recaptchaUrl);
        $data = $this->client->getResponse()->getContent();
        print($data.PHP_EOL);
        preg_match("@\('(.+?)'@is", $data, $reload);
        $reload = $reload[1];
        dump($reload);

        $image_re = 'http://www.google.com/recaptcha/api/image?c=' . $reload . '&th=';

        $this->client->request('GET', $image_re);
        $image = $this->client->getResponse()->getContent();

        if ($this->debug) {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "Captcha Solving" . PHP_EOL);
            }
        }
        $temp_img = 'so_captcha_' . str_slug($this->state->state_name) . '.jpg';
        Storage::put($temp_img, $image);
        $temp_img = storage_path() . '/app/' . $temp_img;
        $decaptcha = new Decaptcha($temp_img);
        $captcha_text = $decaptcha->process();
        dump($captcha_text);
        $this->debug = true;
        if ($this->debug) {
            echo "C: " . $captcha_text . PHP_EOL;
            if (App::runningInConsole()) {
                fwrite(STDOUT, "Captcha Solved" . PHP_EOL);
            }
        }
        $this->debug = false;
        $fields = array();
        $fields['__RequestVerificationToken'] = $this->token;
        $fields['challenge'] = $challenge;
        $fields['response'] = $captcha_text;
        $url = $this->url_base . "VerifyCaptchaJson";
        dump($fields);
        dump($url);
        $this->crawler = $this->client->request('POST', $url, $fields);
        $json = $this->client->getResponse()->getContent();
        $data = json_decode($json);
        dump($data);
        dump($data->success);
        if ($this->debug) {
            var_dump($data);
        }
        dd('-------------');
        unlink($temp_img);
        if (!$data->captchaValid) {
            if (App::runningInConsole()) {
                fwrite(STDOUT, "Captcha Failed : Resubmitting" . PHP_EOL);
            }
            $this->recaptcha_submit();
        }
    }

    private function estimate_time()
    {
        // clear history
        $history = $this->client->getHistory();
        $history->clear();
        //
        if(empty($this->start_time)){
            $this->start_time = time();
        }
        $this->state->records_crawled = $this->records_crawled;
        $remaining_records = ($this->expected_records - $this->records_crawled);
        $record_time = time() - $this->start_time;
        $expected_time = round($record_time * $remaining_records);
        $this->state->records_expected = $this->expected_records;
        $this->state->expected_time = Carbon::now()->addSeconds($expected_time);
        $this->state->save();
        // Check Crawl State
        $this->check_state();
    }
}
