 <?php $__env->startSection('content'); ?>
<?php if(session()->has('message')): ?>
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo session()->get('message'); ?></div> 
<?php endif; ?>
<?php if(session()->has('not_permitted')): ?>
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div> 
<?php endif; ?>

<section class="no-search">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mt-2">
                <h3 class="text-center"><?php echo e(trans('file.Demandes de retrait')); ?></h3>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="demande-table" class="table demande-list stripe" style="width: 100%">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th><?php echo e(trans('file.Date')); ?></th>
                    <th><?php echo e(trans('file.Name')); ?></th>
                    <th><?php echo e(trans('file.action')); ?></th>
                </tr>
            </thead>
            
            <tfoot class="tfoot active">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>                
            </tfoot>
        </table>
    </div>
</section>

<script type="text/javascript">

    $("ul#withdraw").siblings('a').attr('aria-expanded','true');
    $("ul#withdraw").addClass("show");
    $("ul#withdraw #withdraw-demandes-menu").addClass("active");

    var all_permission = <?php echo json_encode($all_permission) ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var searchstring = urlParams.get('search_string');
    $('input[name=search_string]').val(searchstring);
    var search_string = $("#search_string").val();

    $('#demande-table').DataTable( {
        "processing": true,
        "serverSide": true,
        // "searching": false,
        "ordering": false,
        "ajax":{
            url:"./demande-data",
            data:{
                all_permission: all_permission,
                search_string: search_string
            },
            dataType: "json",
            type:"post"
        },
        "createdRow": function( row, data, dataIndex ) {
            $(row).addClass('demande-link');
            $(row).attr('data-demande', data['demande']);
        },
        "columns": [
            {"data": "key"},
            {"data": "v_date"},
            {"data": "v_name"},
            {"data": "options"},
        ],
        'language': {            
            'lengthMenu': '_MENU_ <?php echo e(trans("file.records per page")); ?>',
            "info":      '<small><?php echo e(trans("file.Showing")); ?> _START_ - _END_ (_TOTAL_)</small>',
            "search":  '<?php echo e(trans("file.Search")); ?>',
            'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
            }
        },
        order:[['1', 'desc']],
        'columnDefs': [
            // {
            //     "orderable": false,
            //     'targets': [0, 1, 5, 6, 8, 9, 10],
            // },
            // {
            //     'targets': 4,
            //     className: 'noVis'
            // },
            // {
            //     'targets': [5],
            //     'visible': false
            // },
            {
                'render': function(data, type, row, meta){
                    if(type === 'display'){
                        data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }

                   return data;
                },
                'checkboxes': {
                   'selectRow': true,
                   'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            // <?php if(Auth::user()->role_id == 1) { ?>
            // {
            //     text: '<?php echo e(trans("file.Payée")); ?>',
            //     className: 'btn-success',
            //     action: function ( e, dt, node, config ) {
            //         if(user_verified == '1') {
            //             sale_id.length = 0;
            //             $(':checkbox:checked').each(function(i){
            //                 if(i){
            //                     var sale = $(this).closest('tr').data('withdraw');
            //                     sale_id[i-1] = sale[1];
            //                 }
            //             });
            //             if(sale_id.length && confirm("Vous êtes sur de changer en Payée ?")) {
            //                 $.LoadingOverlay("show");
            //                 $.ajax({
            //                     type:'POST',
            //                     url:'withdraw/paidbyselection',
            //                     data:{
            //                         saleIdArray: sale_id
            //                     },
            //                     success:function(data){
            //                         //alert(data);
            //                         //dt.rows({ page: 'current', selected: true }).deselect();
            //                         //dt.rows({ page: 'current', selected: true }).remove().draw(false);
            //                         location.reload();
            //                     }
            //                 });
            //             }
            //             else if(!sale_id.length)
            //                 alert('Nothing is selected!');
            //         }
            //         else
            //             alert('This feature is disable for demo!');
            //     }
            // },
            // {
            //     text: '<?php echo e(trans("file.En virement")); ?>',
            //     className: 'btn-warning',
            //     action: function ( e, dt, node, config ) {
            //         if(user_verified == '1') {
            //             sale_id.length = 0;
            //             $(':checkbox:checked').each(function(i){
            //                 if(i){
            //                     var sale = $(this).closest('tr').data('withdraw');
            //                     sale_id[i-1] = sale[1];
            //                 }
            //             });
            //             if(sale_id.length && confirm("Vous êtes sur de changer en Virement ?")) {
            //                 $.LoadingOverlay("show");
            //                 $.ajax({
            //                     type:'POST',
            //                     url:'withdraw/virementbyselection',
            //                     data:{
            //                         saleIdArray: sale_id
            //                     },
            //                     success:function(data){
            //                         //alert(data);
            //                         //dt.rows({ page: 'current', selected: true }).deselect();
            //                         //dt.rows({ page: 'current', selected: true }).remove().draw(false);
            //                         location.reload();
            //                     }
            //                 });
            //             }
            //             else if(!sale_id.length)
            //                 alert('Nothing is selected!');
            //         }
            //         else
            //             alert('This feature is disable for demo!');
            //     }
            // },
            // <?php } ?>
            // {
            //     extend: 'colvis',
            //     text: '<?php echo e(trans("file.Column visibility")); ?>',
            //     //columns: ':not(.noVis)'
            //     columns: ':gt(0)'
            // },
        ],
        // drawCallback: function () {
        //     var api = this.api();
        //     datatable_sum(api, false);
        // }
    } );
    
    // function datatable_sum(dt_selector, is_calling_first) {
    //     if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
    //         var rows = dt_selector.rows( '.selected' ).indexes();

    //         $( dt_selector.column( 4 ).footer() ).html(dt_selector.cells( rows, 4, { page: 'current' } ).data().sum());

    //         $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
    //     }
    //     else {
    //         $( dt_selector.column( 4 ).footer() ).html(dt_selector.cells( rows, 4, { page: 'current' } ).data().sum());

    //         $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
    //     }
    // }

</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bota2375/app.bougteba.com/resources/views/withdraw/demandes-retrait.blade.php ENDPATH**/ ?>