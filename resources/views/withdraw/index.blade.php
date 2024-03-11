@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif

<section class="no-search">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mt-2">
                <h3 class="text-center">{{trans('file.Withdraw List')}}</h3>
            </div>
            {!! Form::open(['route' => 'withdraw.index', 'method' => 'get']) !!}
            <div class="row no-mrl mb-3">
                <div class="col-md-4 mt-3">
                    <div class="form-group row">
                        <label class="d-tc mt-2"><strong>{{trans('file.Search')}}</strong> &nbsp;</label>
                        <div class="d-tc">
                            <input type="text" name="search_string" id="search_string" class="form-control" placeholder="{{ trans('file.Type to search...') }}">
                            <p class="no-mb">
                                <small>(facture no...)</small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="form-group row">
                        <label class="d-tc mt-2"><strong>{{trans('file.Choose Status')}}</strong> &nbsp;</label>
                        <div class="d-tc">
                            <select id="status_id" name="status_id" class="selectpicker form-control" data-live-search="true" >
                                <option value="2">{{trans('file.All Status')}}</option>
                                <option value="1">{{trans('file.Payée')}}</option>
                                <option value="0">{{trans('file.En virement')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mt-3">
                    <div class="form-group">
                        <button class="btn btn-primary" id="filter-btn" type="submit">{{trans('file.submit')}}</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="table-responsive">
        <table id="withdraw-table" class="table withdraw-list stripe" style="width: 100%">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.Name')}}</th>
                    <th>{{trans('file.Invoice Number')}}</th>
                    <th>{{trans('file.Sales Number')}}</th>
                    <th>{{trans('file.Sales Reference No')}}</th>
                    <th>{{trans('file.Montant Total')}}</th>
                    <th>{{trans('file.Status')}}</th>
                    <th>{{trans('file.Facture')}}</th>
                </tr>
            </thead>
            
            <tfoot class="tfoot active">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: right;">{{trans('file.Total')}}</th>
                    <th></th>
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
    $("ul#withdraw #withdraw-list-menu").addClass("active");

    var all_permission = <?php echo json_encode($all_permission) ?>;
    var sale_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var status = urlParams.get('status_id');
    $('select[name=status_id]').val(status);
    $('.selectpicker').selectpicker('refresh');
    var searchstring = urlParams.get('search_string');
    $('input[name=search_string]').val(searchstring);

    var status_id = $("#status_id").val();
    var search_string = $("#search_string").val();

    $('#withdraw-table').DataTable( {
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ordering": false,
        "ajax":{
            url:"withdraw/withdraw-data",
            data:{
                all_permission: all_permission,
                search_string: search_string,
                status_id: status_id
            },
            dataType: "json",
            type:"post"
        },
        "createdRow": function( row, data, dataIndex ) {
            $(row).addClass('withdraw-link');
            $(row).attr('data-withdraw', data['withdraw']);
        },
        "columns": [
            {"data": "key"},
            {"data": "date"},
            {"data": "v_name"},
            {"data": "facture_no"},
            {"data": "sales_total"},
            {"data": "sales_reference_no"},
            {"data": "montant_total"},
            {"data": "f_status"},
            {"data": "options"},
        ],
        'language': {            
            'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
            "info":      '<small>{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)</small>',
            "search":  '{{trans("file.Search")}}',
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
            {
                'targets': [5],
                'visible': false
            },
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
            <?php if (in_array("withdraw-index-pay", $all_permission)) { ?>
            {
                text: '{{trans("file.Payée")}}',
                className: 'btn-success',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        sale_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                var sale = $(this).closest('tr').data('withdraw');
                                sale_id[i-1] = sale[1];
                            }
                        });
                        if(sale_id.length && confirm("Vous êtes sur de changer en Payée ?")) {
                            $.LoadingOverlay("show");
                            $.ajax({
                                type:'POST',
                                url:'withdraw/paidbyselection',
                                data:{
                                    saleIdArray: sale_id
                                },
                                success:function(data){
                                    //alert(data);
                                    //dt.rows({ page: 'current', selected: true }).deselect();
                                    //dt.rows({ page: 'current', selected: true }).remove().draw(false);
                                    location.reload();
                                }
                            });
                        }
                        else if(!sale_id.length)
                            alert('Nothing is selected!');
                    }
                    else
                        alert('This feature is disable for demo!');
                }
            },
            {
                text: '{{trans("file.En virement")}}',
                className: 'btn-warning',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        sale_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                var sale = $(this).closest('tr').data('withdraw');
                                sale_id[i-1] = sale[1];
                            }
                        });
                        if(sale_id.length && confirm("Vous êtes sur de changer en Virement ?")) {
                            $.LoadingOverlay("show");
                            $.ajax({
                                type:'POST',
                                url:'withdraw/virementbyselection',
                                data:{
                                    saleIdArray: sale_id
                                },
                                success:function(data){
                                    //alert(data);
                                    //dt.rows({ page: 'current', selected: true }).deselect();
                                    //dt.rows({ page: 'current', selected: true }).remove().draw(false);
                                    location.reload();
                                }
                            });
                        }
                        else if(!sale_id.length)
                            alert('Nothing is selected!');
                    }
                    else
                        alert('This feature is disable for demo!');
                }
            },
            <?php } ?>
            {
                extend: 'colvis',
                text: '{{trans("file.Column visibility")}}',
                //columns: ':not(.noVis)'
                columns: ':gt(0)'
            },
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum(api, false);
        }
    } );
    
    function datatable_sum(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();

            $( dt_selector.column( 4 ).footer() ).html(dt_selector.cells( rows, 4, { page: 'current' } ).data().sum());

            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 4 ).footer() ).html(dt_selector.cells( rows, 4, { page: 'current' } ).data().sum());

            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    // function deliveryDetails(delivery) {
    //     // $('input[name="delivery_id"]').val(delivery[3]);
    //     $("#delivery-content tbody").remove();
    //     var newBody = $("<tbody>");
    //     var rows = status = '';
    //     rows += '<tr><td>Date</td><td>'+delivery[0]+'</td></tr>';
    //     rows += '<tr><td>Delivery Reference</td><td>'+delivery[1]+'</td></tr>';
    //     rows += '<tr><td>Sale Reference</td><td>'+delivery[2]+'</td></tr>';
    //     rows += '<tr><td>Customer Name</td><td>'+delivery[4]+'</td></tr>';
    //     rows += '<tr><td>Phone</td><td>'+delivery[5]+'</td></tr>';
    //     var customer_address = delivery[6].split("<br><br>").join("<br>");
    //     rows += '<tr><td>Address</td><td>'+customer_address+'<br>'+delivery[7]+'</td></tr>';
    //     var delivery_note = delivery[8].split("<br><br>").join("<br>");
    //     rows += '<tr><td>Note</td><td>'+delivery_note+'</td></tr>';        

    //     newBody.append(rows);
    //     $("table#delivery-content").append(newBody);

    //     $.get('delivery/delivery_status/' + delivery[3], function(data) {
    //         $(".delivery-status-list tbody").remove();
    //         var status = data[0];
    //         var status_date = data[1];
    //         var newBody = $("<tbody>");
    //         $.each(status, function(index) {
    //             var newRow = $("<tr>");
    //             var cols = '';
    //             switch(status[index]) {
    //                 case "1":
    //                     cols += '<td><div class="badge badge-warning">Pickup</div></td>';
    //                     break;
    //                 case "2":
    //                     cols += '<td><div class="badge badge-info">Sent</div></td>';
    //                     break;
    //                 case "3":
    //                     cols += '<td><div class="badge badge-primary">Mise en distribution</div></td>';
    //                     break;
    //                 case "4":
    //                     cols += '<td><div class="badge badge-success">Delivered</div></td>';
    //                     break;
    //                 case "5":
    //                     cols += '<td><div class="badge badge-danger">Ne répond pas</div></td>';
    //                     break;
    //                 case "6":
    //                     cols += '<td><div class="badge badge-danger">Injoignable</div></td>';
    //                     break;
    //                 case "7":
    //                     cols += '<td><div class="badge badge-danger">Erreur numéro</div></td>';
    //                     break;
    //                 case "8":
    //                     cols += '<td><div class="badge badge-danger">Reporté</div></td>';
    //                     break;
    //                 case "9":
    //                     cols += '<td><div class="badge badge-danger">Programmé</div></td>';
    //                     break;
    //                 case "10":
    //                     cols += '<td><div class="badge badge-danger">Annulé</div></td>';
    //                     break;
    //                 case "11":
    //                     cols += '<td><div class="badge badge-danger">Refusé</div></td>';
    //                     break;
    //                 case "12":
    //                     cols += '<td><div class="badge badge-danger">Retourné</div></td>';
    //                     break;
    //             }
                
    //             cols += '<td>' + status_date[index] + '</td>';
    //             newRow.append(cols);
    //             newBody.append(newRow);
    //         });
    //         $("table.delivery-status-list").append(newBody);
    //     });

    //     $.get('delivery/product_delivery/' + delivery[3], function(data) {
    //         $(".product-delivery-list tbody").remove();
    //         var code = data[0];
    //         var description = data[1];
    //         var qty = data[2];
    //         var newBody = $("<tbody>");
    //         $.each(code, function(index) {
    //             var newRow = $("<tr>");
    //             var cols = '';
    //             cols += '<td><strong>' + (index+1) + '</strong></td>';
    //             cols += '<td>' + code[index] + '</td>';
    //             cols += '<td>' + description[index] + '</td>';
    //             cols += '<td>' + qty[index] + '</td>';
    //             newRow.append(cols);
    //             newBody.append(newRow);
    //         });
    //         $("table.product-delivery-list").append(newBody);
    //     });

    //     var htmlfooter = '<div class="col-md-6 form-group"><p>Delivered By: '+delivery[10]+'</p></div>';
    //     // htmlfooter += '<div class="col-md-6 form-group"><img style="max-width:850px;height:100%;max-height:130px" src="data:image/png;base64,'+barcode+'" alt="barcode" /></div>';
    //     htmlfooter += '<br><br><br><br>';
    //     htmlfooter += '';

    //     $('#delivery-footer').html(htmlfooter);
    //     $('#delivery-details').modal('show');
    // }

    // // function deliveryDetails(delivery){
    // //     //alert(sale);
    // //     if(sale[9] == 1) //is_valide
    // //         var valide_status = '<div class="badge badge-success">{{trans("file.Confirmed")}}</div>';
    // //     else
    // //         var valide_status = '<div class="badge badge-warning">{{trans("file.Not Confirmed")}}</div>';

    // //     if (sale[7] == 1) 
    // //         var sale_delivery = '<div class="badge badge-warning">Pickup<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 2)
    // //         var sale_delivery = '<div class="badge badge-info">Sent<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 3)
    // //         var sale_delivery = '<div class="badge badge-primary">Distribution<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 4)
    // //         var sale_delivery = '<div class="badge badge-success">Delivered<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 5)
    // //         var sale_delivery = '<div class="badge badge-danger">Ne répond pas<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 6)
    // //         var sale_delivery = '<div class="badge badge-danger">Injoignable<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 7)
    // //         var sale_delivery = '<div class="badge badge-danger">Erreur numéro<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 8)
    // //         var sale_delivery = '<div class="badge badge-danger">reporté<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 9)
    // //         var sale_delivery = '<div class="badge badge-danger">Programmé<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 10)
    // //         var sale_delivery = '<div class="badge badge-danger">Annulé<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 11)
    // //         var sale_delivery = '<div class="badge badge-danger">Refusé<br>'+sale[20]+'</div>';
    // //     else if (sale[7] == 12)
    // //         var sale_delivery = '<div class="badge badge-danger">Retourné<br>'+sale[20]+'</div>';
    // //     else
    // //         var sale_delivery = '<div class="badge badge-secondary">{{trans("file.Pas de livraison")}}</div>';
            
    // //     var customer_add = sale[5].split("<br><br>").join("<br>");
    // //     var sale_note = sale[13].split("<br><br>").join("<br>");
    // //     var sale_staff = sale[14].split("<br><br>").join("<br>");
            
    // //     var htmltext = '<div class="row"><div class="col-md-6"><table><tr><td><u><strong>{{trans("file.Date")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[0]+'</tr><tr><td><u><strong>{{trans("file.reference")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[1]+'</tr></table></div><div class="col-md-6"><table><tr><td><u><strong>{{trans("file.Status")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+valide_status+'</tr><tr><td><u><strong>{{trans("file.Delivery")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale_delivery+'</td></tr></table></div></div><br><br><div class="row"><div class="col-md-12"><u><strong>{{trans("file.customer")}}</strong></u> : <br>'+sale[3]+'<br>'+sale[4]+'<br>'+customer_add+'<br>'+sale[6]+'</div></div>';
    // //     //var htmltext = '<div class="row"><div class="col-md-6"><table><tr><td><u><strong>{{trans("file.Date")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[0]+'</tr><tr><td><u><strong>{{trans("file.reference")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[1]+'</tr></table></div><div class="col-md-6"><table><tr><td><u><strong>{{trans("file.Status")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+valide_status+'</tr><tr><td><u><strong>{{trans("file.delivery")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale_delivery+'</td></tr></table></div></div><br><br><div class="row"><div class="col-md-12"><u><strong>{{trans("file.customer")}}</strong></u> : <br>'+sale[3]+'<br>'+sale[4]+'<br>'+sale[5]+'<br>'+sale[6]+'</div></div>';

    // //     $.get('sales/product_sale/' + sale[10], function(data){
    // //         $(".product-sale-list tbody").remove();
    // //         var name_code = data[0];
    // //         var qty = data[1];
    // //         var subtotal = data[6];
    // //         var livraison = data[8];
    // //         var newBody = $("<tbody>");
    // //         $.each(name_code, function(index){
    // //             var newRow = $("<tr>");
    // //             var cols = '';
    // //             cols += '<td><strong>' + (index+1) + '</strong></td>';
    // //             cols += '<td>' + name_code[index] + '</td>';
    // //             cols += '<td>' + qty[index] + '</td>';
    // //             cols += '<td>' + parseFloat(subtotal[index] / qty[index]).toFixed(2) + '</td>';
    // //             cols += '<td>' + parseFloat(subtotal[index]).toFixed(2) + '</td>';
    // //             newRow.append(cols);
    // //             newBody.append(newRow);
    // //         });

    // //         var newRow = $("<tr>");
    // //         cols = '';
    // //         cols += '<td colspan=4><strong>{{trans("file.grand total")}}:</strong></td>';
    // //         cols += '<td>' + parseFloat(sale[12]).toFixed(2) + '</td>';
    // //         newRow.append(cols);
    // //         newBody.append(newRow);

    // //         $("table.product-sale-list").append(newBody);
    // //     });

    // //     var htmlfooter = '<div class="row"><div class="col-md-6"><u><strong>{{trans("file.Sale Note")}}:</strong></u></div><div class="col-md-6"><u><strong>{{trans("file.Staff Note")}}:</strong></u></div></div><div class="row"><div class="col-md-6">'+sale_note+'</div><div class="col-md-6">'+sale_staff+'</div></div>';
    // //     $('#sale-content').html(htmltext);
    // //     $('#sale-footer').html(htmlfooter);
    // //     $('#sale-details').modal('show');
    // // }

    // $(document).on('submit', '.payment-form', function(e) {
    //     if( $('input[name="paying_amount"]').val() < parseFloat($('#amount').val()) ) {
    //         alert('Paying amount cannot be bigger than recieved amount');
    //         $('input[name="amount"]').val('');
    //         $(".change").text(parseFloat( $('input[name="paying_amount"]').val() - $('#amount').val() ).toFixed(2));
    //         e.preventDefault();
    //     }
    //     else if( $('input[name="edit_paying_amount"]').val() < parseFloat($('input[name="edit_amount"]').val()) ) {
    //         alert('Paying amount cannot be bigger than recieved amount');
    //         $('input[name="edit_amount"]').val('');
    //         $(".change").text(parseFloat( $('input[name="edit_paying_amount"]').val() - $('input[name="edit_amount"]').val() ).toFixed(2));
    //         e.preventDefault();
    //     }
        
    //     $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', false);
    // });
    
    // if(all_permission.indexOf("sales-delete") == -1)
    //     $('.buttons-delete').addClass('d-none');

    //     function confirmDelete() {
    //         if (confirm("Are you sure want to delete?")) {
    //             return true;
    //         }
    //         return false;
    //     }

    // function confirmPaymentDelete() {
    //     if (confirm("Are you sure want to delete? If you delete this money will be refunded.")) {
    //         return true;
    //     }
    //     return false;
    // }

    /*******/

</script>
@endsection

@section('scripts')


@endsection