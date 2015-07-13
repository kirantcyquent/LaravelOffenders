var TableAjax = function () {
    return {
        //main function to initiate the module
        init: function (columns) {
            //handleRecords(columns);
            var table = $('#datatable_ajax').DataTable({
                processing: true,
                serverSide: true,
                loadingMessage: 'Loading...',
                ajax: $("#datatable_ajax").data('url'),
                "columns": columns,
                "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"] // change per page values here
                ],
                "pageLength": 10
            });
            table.order( [[2,"asc"]]);
        }

    };

}();
var table = $('#datatable_ajax');
$('.group-checkable', table).change(function() {
    var set = $('tbody > tr > td:nth-child(1) input[type="checkbox"]', table);
    var checked = $(this).is(":checked");
    $(set).each(function() {
        $(this).attr("checked", checked);
    });
    $.uniform.update(set);
});