<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use Queue;

use App\Sexoffender;
use App\SexoffendersStat;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Storage;
class SexoffendersController extends Controller
{
    //
    public function index()
    {
        return view('sexoffenders.index');
    }

    public function start()
    {
        return view('sexoffenders.start');
    }

    public function data_start(Request $request)
    {
        $sexoffenders = Sexoffender::select(array('id', 'state_name', 'state_code', 'state_url','completed_at'))->where('crawl_state', '=', 'stopped');

        return Datatables::of($sexoffenders)
            ->addColumn('actions', '<button data-url="/sexoffenders/queue?id={{$id}}&amp;action=start" class="btn btn-xs btn-success dt-start" ><i class="fa  fa-play"></i> Start</button>')
            ->editColumn('id', '<input type="checkbox" value="{{$id}}" name="id[]">')
            ->editColumn('state_name', '<a href="http://www.nullrefer.com/?{{$state_url}}" target="_blank">{{$state_name}}</a>')
            ->editColumn('completed_at', function ($state) {
                if (empty($state->completed_at)) {
                    return '';
                } else {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $state->completed_at)->toDayDateTimeString();
                }
            })
            ->make(true);
    }

    public function paused()
    {
        return view('sexoffenders.paused');
    }

    public function data_paused(Request $request)
    {
        $sexoffenders = Sexoffender::select(array('id', 'state_name', 'state_code', 'state_url', 'records_crawled', 'records_expected', 'started_at', 'expected_time'))->where('crawl_state', '=', 'paused');

        return Datatables::of($sexoffenders)
            ->addColumn('actions', '<button data-url="/sexoffenders/queue?id={{$id}}&amp;action=resume" class="btn btn-xs btn-warning dt-action" ><i class="fa  fa-play-circle"></i> Resume</button>
                                    <button data-url="/sexoffenders/queue?id={{$id}}&amp;action=stop" class="btn btn-xs btn-danger dt-stop" data-toggle="confirmation"><i class="fa  fa-stop"></i> Stop</button>
                                    <button data-url="/sexoffenders/queue?id={{$id}}&amp;action=start" class="btn btn-xs btn-success dt-action" ><i class="fa  fa-refresh"></i> Restart</button>')
            ->addColumn('history', '<a class="btn default btn-xs blue" data-toggle="modal" href="/sexoffenders/history?id={{$id}}" data-target="#history"><i class="fa fa-history"></i> History </a>')
            ->editColumn('started_at', function ($state) {
                if (empty($state->started_at)) {
                    return '';
                } else {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $state->started_at)->toDayDateTimeString();
                }
            })
            ->editColumn('id', '<input type="checkbox" value="{{$id}}" name="id[]">')
            ->editColumn('state_name', '<a href="http://www.nullrefer.com/?{{$state_url}}" target="_blank">{{$state_name}}</a>')
            ->make(true);
    }

    public function running()
    {
        return view('sexoffenders.running');
    }

    public function data_running(Request $request)
    {
        $sexoffenders = Sexoffender::select(array('id', 'state_name', 'state_code', 'state_url', 'records_crawled','status', 'records_expected', 'started_at', 'expected_time'))->where('crawl_state', '=', 'running');

        return Datatables::of($sexoffenders)
            ->addColumn('actions', '<button data-url="/sexoffenders/queue?id={{$id}}&amp;action=stop" class="btn btn-xs btn-danger dt-stop" data-toggle="confirmation"><i class="fa  fa-stop"></i> Stop</button>
                                    <button data-url="/sexoffenders/queue?id={{$id}}&amp;action=pause" class="btn btn-xs btn-warning dt-pause" ><i class="fa  fa-pause"></i> Pause</button>')
            ->addColumn('history', '<a class="btn default btn-xs blue" data-toggle="modal" href="/sexoffenders/history?id={{$id}}" data-target="#history"><i class="fa fa-history"></i> History </a>')
            ->addColumn('elapsed_time', function ($state) {
                if (empty($state->started_at)) {
                    return '';
                } else {
                    return '<span title="'.Carbon::createFromFormat('Y-m-d H:i:s', $state->started_at)->toDayDateTimeString().'">'.Carbon::createFromFormat('Y-m-d H:i:s', $state->started_at)->diffForHumans().'</span>';
                }
            })
            ->addColumn('records',  function ($state) {
                if (empty($state->records_expected)) {
                    return '<i class="icon-speedometer"></i> Calculating';
                } else {
                    $path = 'sexoffenders/' . snake_case($state->state_name) . '/';
                    $image_count = count(Storage::files($path));
                    return $state->records_crawled." / ".$state->records_expected." <sup> ( <i class='fa fa-image'></i> ".$image_count.") </sup>";
                }
            })
            ->editColumn('expected_time', function ($state) {
                if (empty($state->expected_time)) {
                    return '<i class="icon-speedometer"></i> Calculating';
                } else {
                    $expected_time = Carbon::createFromFormat('Y-m-d H:i:s', $state->expected_time);
                    if($expected_time->diffInSeconds() < 150){
                        $expected_time = Carbon::now()->addSeconds(150);
                    }
                    return $expected_time->diffForHumans();
                }
            })
            ->editColumn('started_at', function ($state) {
                if (empty($state->started_at)) {
                    return '';
                } else {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $state->started_at)->toDayDateTimeString();
                }
            })
            ->editColumn('id', '<input type="checkbox" value="{{$id}}" name="id[]">')
            ->editColumn('state_name', '<a href="http://www.nullrefer.com/?{{$state_url}}" target="_blank">{{$state_name}}</a>')
            ->make(true);
    }

    public function completed()
    {
        return view('sexoffenders.completed');
    }

    //
    public function data_completed(Request $request)
    {
        $sexoffenders = SexoffendersStat::join('sexoffenders','sexoffenders_stats.sexoffender_id','=','sexoffenders.id')
            ->select(array('sexoffenders_stats.id', 'sexoffenders.state_name', 'sexoffenders.state_code', 'sexoffenders.state_url', 'sexoffenders_stats.started_at','sexoffenders_stats.completed_at','sexoffenders_stats.records_crawled'));

        $start = $request->get('start');
        return Datatables::of($sexoffenders)
            ->addColumn('actions', function ($state) {
                $date_completed = $state->completed_at->format('d-m-Y');
                $file = strtolower($state->state_code).'-'.$date_completed;
                return '<a href="http://rt.midatha.com.s3-website-us-west-2.amazonaws.com/sexoffenders/'.$file.'.zip" class="btn btn-xs btn-danger dt-delete"><i class="fa  fa-download"></i> Download</a>';
            })
            ->editColumn('id', function () use (&$start) {
                $start++;
                return $start;
            })
            ->editColumn('state_name', '<a href="http://www.nullrefer.com/?{{$state_url}}" target="_blank">{{$state_name}}</a>')
            ->make(true);
    }

    public function history(Request $request)
    {

        $id = $request->get('id');
        $state = Sexoffender::findOrFail($id);
        $state_name = $state->state_name;
        return view('modal',compact('state_name'));
    }
    public function queue(Request $request)
    {
        $action = $request->get('action');
        $id = $request->get('id');
        $state = Sexoffender::findOrFail($id);
        $state_name = $state->state_name;
        $class = "\App\Jobs\Sexoffenders\\".$state_name;
        switch ($action) {
            case "start":
                $state->records_crawled = 0;
                $state->records_expected = 0;
                $state->expected_time = NULL;
                $state->crawl_state = 'running';
                $state->status = 'Waiting ...';
                $state->started_at = Carbon::now();
                $state->save();
                $this->dispatch(new $class($state));
                break;
            case "stop":
                $state->crawl_state = 'stopped';
                $state->paused = '0';
                $state->save();

                break;
            case "pause":
                $state->crawl_state = 'paused';
                $state->paused = '1';
                $state->save();
                // save paused state
                break;
            case "resume":
                $state->crawl_state = 'running';
                $state->save();
                $this->dispatch(new $class($state));
                break;
        }
    }
}
