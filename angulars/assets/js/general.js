$(document).ready(function () {
   initTables();

    $(".sidebar-toggle").click(function () {
        if ($('#body').hasClass('sidebar-collapse'))
        {
//            settogglenavigation('sidebar-collapse');

        } else
        {
//            settogglenavigation('sidebar-collapsenon');
        }
        // window.location.href=base_url+"users/dashboard";
    });
});
function settogglenavigation(getvalue)
{
    $.ajax({
        url: base_url + "users/settogglenavigation",
        type: 'post',
        data: {setdefaulttoggle: getvalue},
        success: function (data) {

        },
        error: function (xhr, desc, err) {

        }
    });
}
function initTables() {
    $("table.dyntable:visible").each(function (i, ele) {
        var ele = $(ele);
        var source = ele.attr('source');
        var jsonStr = ele.attr('jsonInfo');
        var max_rows = ele.attr("max_rows");
        var order_by = ele.attr("data-order-by");

        ele.dataTable({
            "searchable": true,
            "pageLength": 10,
            "sortable": true,
            "serverSide": true,
            "processing": true,
            "pagingType": "full_numbers",
            "ajax": source,
            "order": [eval(order_by)],
            "columns": eval(jsonStr)
        });

        ele.dataTable().fnFilterOnReturn();
    });


}