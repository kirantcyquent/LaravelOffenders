<?php namespace App\Jobs\Traits;


use App\Sexoffender;
use App\SexoffendersStat;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Process\Process;
use Intervention\Image\Facades\Image;

trait SexoffendersCrawler
{
    protected $debug = false;
    protected $client;
    protected $crawler;
    protected $state;
    protected $url_base;
    protected $token;
    protected $profiles_table = true;
    protected $counties_table = true;
    protected $records_crawled = 0;
    protected $expected_records = 0;
    protected $start_time;
    protected $end_time;
    protected $counties;

    public function handle()
    {
        $this->client();
        echo PHP_EOL . "** Sex Offender: " . $this->state->state_name . " Crawl Started : " . Carbon::now()->toDateTimeString() . PHP_EOL;
        $this->start();
    }

    private function client()
    {
        $this->client = new Client();
        $this->client->getClient()->setDefaultOption('config', ['curl' => [ 'CURLOPT_TIMEOUT' => 900, 'CURLOPT_SSL_VERIFYPEER' => false, 'CURLOPT_SSL_VERIFYHOST' => false, 'CURLOPT_CERTINFO' => false]]);
        $this->client->setHeader('User-Agent', "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0");
        //
    }

    private function check_state()
    {
        $state = Sexoffender::find($this->state->id);
        if ($state->crawl_state != 'running') {
            if ($state->crawl_state == 'stopped') {
                DB::table('de_profiles')->update(array('status' => '0'));
            }
            return;
        }

    }

    /**
     *
     */
    private function completed()
    {
        echo $this->state->state_name . ": Crawl Completed : " . Carbon::now()->toDateTimeString() . PHP_EOL;

        $this->state->status = "Post Crawl Job";
        $this->state->save();

        $this->create_packages();

        $completed_at = Carbon::now();
        $crawl_time = $completed_at->diffInSeconds($this->state->started_at);
        if ($this->records_crawled) {
            $record_time = round($crawl_time / $this->records_crawled);
        } else {
            $record_time = 0;
        }
        $this->state->completed_at = $completed_at;
        $this->state->crawl_state = 'stopped';
        $this->state->save();

        $stats = new SexoffendersStat;
        $stats->sexoffender_id = $this->state->id;
        $stats->started_at = $this->state->started_at;
        $stats->completed_at = $completed_at;
        $stats->records_crawled = $this->records_crawled;
        $stats->crawl_time = gmdate("H:i:s", (int)$crawl_time);
        $stats->record_time = gmdate("H:i:s", (int)$record_time);
        $stats->save();

        $this->reset_crawl();
        return;
    }

    /**
     *
     */
    private function create_packages()
    {

        echo $this->state->state_name . ": Creating Packages ";
        $db_host = config('database.connections.mysql.host');
        $db_username = config('database.connections.mysql.username');
        $db_password = config('database.connections.mysql.password');
        $db_name = config('database.connections.mysql.database');

        $state = snake_case($this->state->state_name);
        $state_code = strtolower($this->state->state_code);
        $state_folder = storage_path() . '/app/sexoffenders/' . $state;
        $sql_file = $state_folder . '.sql.gz';
        $mysql_cmd = 'mysqldump -h ' . $db_host . '  -u ' . $db_username . ' -p' . $db_password . ' ' . $db_name . ' ' . $state_code . '_offenders ' . $state_code . '_offenses | gzip -c > ' . $sql_file;
        $process = new Process($mysql_cmd);
        $process->setTimeout(5 * 60);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
        $date_completed = Carbon::now()->format('d-m-Y');
        $zip_file = strtolower($this->state->state_code) . '-' . $date_completed . '.zip';
        $local_file = storage_path() . '/app/sexoffenders/' . $zip_file;
        $zip_cmd = 'zip -r ' . $local_file . ' ' . $sql_file . ' ' . $state_folder;
        //echo $zip_cmd.PHP_EOL;
        $process = new Process($zip_cmd);
        $process->setTimeout(5 * 60);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
        echo "Package Created" . PHP_EOL;
        //
        $s3_cmd = 'aws s3 cp ' . $local_file . '  s3://rt.midatha.com/sexoffenders/' . $zip_file;
        //echo $s3_cmd.PHP_EOL;
        $process = new Process($s3_cmd);
        $process->setTimeout(5 * 60);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }else{
            unlink($local_file);
        }
        //
        echo "Package Moved to: AWS S3" . PHP_EOL;
        return;
    }

    /**
     *
     */
    private function reset_crawl()
    {
        echo $this->state->state_name . " Resetting Crawl ... ";
        $state = snake_case($this->state->state_name);
        $state_code = strtolower($this->state->state_code);
        // Delete Crawl Files
        Storage::delete('sexoffenders/' . $state . '.sql.gz');
        Storage::deleteDirectory('sexoffenders/' . $state, true);
        Storage::deleteDirectory('sexoffenders/_html/' . str_slug($this->state->state_name), true);
        // DB Reset
        if ($this->counties_table) {
            DB::table($state_code . '_counties')->update(['status' => '0']);
        }
        DB::table($state_code . '_offenders')->truncate();
        DB::table($state_code . '_offenses')->truncate();
        if ($this->profiles_table) {
            DB::table($state_code . '_profiles')->truncate();
        }
        //
        echo "State Crawl Refreshed" . PHP_EOL;
        return;
    }

    /**
     * @param $filepath Local File Path
     * @return bool|string Extension
     */
    private function getImageExt($filepath)
    {
        try {
            $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
            $allowedTypes = array(
                1, // [] gif
                2, // [] jpg
                3, // [] png
                6, // [] bmp
            );
            if (!in_array($type, $allowedTypes)) {
                return false;
            }
            switch ($type) {
                case 1:
                    $ext = '.gif';
                    break;
                case 2:
                    $ext = '.jpg';
                    break;
                case 3:
                    $ext = '.png';
                    break;
                case 6:
                    $ext = '.bmp';
                    break;
            }
            unset($type);
            return $ext;
        } catch (\ErrorException $e) {
            return '.nofile';
        }
    }

    private function process_image($image, $ext){
        if($ext != '.nofile'){
            $image = storage_path()."/app/".$image;
            $image_jpg = str_replace($ext,'.jpg',$image);
            Image::make($image)->save($image_jpg,100)->destroy();;
            unlink($image);
        }
    }
    /**
     * @param $text
     * @return string
     */
    private function cleanup_text($text)
    {
        return trim(utf8_encode(preg_replace('/[^(\x20-\x7F)]*/', '', preg_replace('/(\s\s+|\t|\n)/', ' ', $text))));
    }

    public function failed()
    {
        // Called when the job is failing...
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
        $mail = 'Mail body';
        Mail::raw($mail, function ($message) {
            $message->to('foo@example.com', 'John Smith')->subject('Crawl discontinued: ');
        });
    }

}
