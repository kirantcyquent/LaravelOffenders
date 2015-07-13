
@extends('app')
@section('css')
    <link rel="stylesheet" type="text/css" href="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
@stop
@section('content')

    <div class="row">
        <div class="col-md-12">

            <!-- Begin: life time stats -->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-users"></i>Sex Offenders - Completed Crawls <sup>( Auto - Refresh in <span id="show_cd"></span> seconds)</sup>
                    </div>
                    <div id="cd_refresh" class="hide"></div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">

                        <table class="table table-striped table-bordered table-hover" id="datatable_ajax" data-url="/sexoffenders/data-completed">
                            <thead>
                            <tr role="row" class="heading">
                                <th width="2%">

                                </th>
                                <th>
                                    State Name
                                </th>
                                <th>
                                    State Code
                                </th>
                                <th>
                                    Start Time
                                </th>
                                <th>
                                    End Time
                                </th>
                                <th>
                                    Records Crawled
                                </th>

                                <th width="54">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>

@stop
@section('scripts')
    <script type="text/javascript" src="/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
    <script src="/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js" type="text/javascript"></script>

    <script src="/assets/global/scripts/datatable.js"></script>
    <script src="/js/table-ajax.js"></script>
@stop
@section('scripts-init')
    var columns = [
        {data: 'id', name: 'id'},
        {data: 'state_name', name: 'sexoffenders.state_name'},
        {data: 'state_code', name: 'sexoffenders.state_code'},
        {data: 'started_at', name: 'sexoffenders_stats.started_at'},
        {data: 'completed_at', name: 'sexoffenders_stats.completed_at'},
        {data: 'records_crawled', name: 'sexoffenders_stats.records_crawled'},
        {data: 'actions', name: 'actions',  searchable: false, orderable: false}
    ];
    TableAjax.init(columns);

    var shortly = new Date();
    var refreshInterval = 30*60;
    shortly.setSeconds(shortly.getSeconds() + refreshInterval);
    $('#cd_refresh').countdown({until: shortly, onExpiry: liftOff,onTick: watchCountdown});
    function liftOff() {
        shortly = new Date();
        shortly.setSeconds(shortly.getSeconds() + refreshInterval);
        $('#cd_refresh').countdown('option', {until: shortly});
        $('#datatable_ajax').DataTable().ajax.reload(null, false);
    }
    function watchCountdown(periods) {
        $('#show_cd').text( periods[6] );
    }
@stop