
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
                        <i class="icon-users"></i>Sex Offenders - Paused Crawlers <sup>( Auto - Refresh in <span id="show_cd"></span> seconds)</sup>
                    </div>
                    <div id="cd_refresh" class="hide"></div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">

                        <table class="table table-striped table-bordered table-hover" id="datatable_ajax" data-url="/sexoffenders/data-paused">
                            <thead>
                            <tr role="row" class="heading">
                                <th width="2%">
                                    <input type="checkbox" class="group-checkable">
                                </th>
                                <th width="215">
                                    Actions
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
                                    Records Crawled
                                </th>
                                <th>
                                    Records Expected
                                </th>
                                <th>
                                    History
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
    <div class="modal fade" id="history" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>
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
        {data: 'id', name: 'id',searchable: false, orderable: false},
        {data: 'actions', name: 'actions',  searchable: false, orderable: false},
        {data: 'state_name', name: 'state_name'},
        {data: 'state_code', name: 'state_code'},
        {data: 'started_at', name: 'started_at', searchable: false},
        {data: 'records_crawled', name: 'records_crawled', searchable: false},
        {data: 'records_expected', name: 'records_expected', searchable: false},
        {data: 'history', name: 'history', searchable: false}
    ];
    TableAjax.init(columns);
    $('#datatable_ajax').on('click', '.dt-stop', function(event){
        event.preventDefault();
        var url = $(this).data('url');

        $(this).confirmation({
            singleton: true,
            popout: true,
            placement:'top'
        });
        $(this).confirmation('show');
        $(this).on('confirmed.bs.confirmation',function(e){
            e.preventDefault();
            $.ajax({
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url, //resource
                success: function() {
                    $('#datatable_ajax').DataTable().ajax.reload(null, false);
                }
            });
        });
    });
    $('#datatable_ajax').on('click','.dt-action',function(event){
        event.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            type: 'GET',
            url: url, //resource
            success: function() {
                $('#datatable_ajax').DataTable().ajax.reload(null, false);
            }
        });
    });

    $('#datatable_ajax').on( 'draw.dt', function () {
        $("input[type=checkbox]").uniform();
    });
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