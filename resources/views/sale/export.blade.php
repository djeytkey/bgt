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
                <h3 class="text-center">{{trans('file.Export List')}}</h3>
                <input type="hidden" name="starting_date" value="{{$starting_date}}" />
                <input type="hidden" name="ending_date" value="{{$ending_date}}" />
                <input type="hidden" name="search_string" id="search_string" class="form-control" />
                <select id="status_id" name="status_id" class="selectpicker form-control d-none" data-live-search="true" >
                    <option value="2">{{trans('file.All Status')}}</option>
                    <option value="1">{{trans('file.Confirmed')}}</option>
                    <option value="0">{{trans('file.Not Confirmed')}}</option>
                </select>
                @if(in_array("sales-add", $all_permission))
                <a href="{{route('sales.index')}}" class="btn btn-success"><i class="dripicons-list"></i> {{trans('file.Sales List')}}</a>&nbsp;
                <a href="{{route('delivery.index')}}" class="btn btn-primary"><i class="dripicons-rocket"></i> {{trans('file.Delivery List')}}</a>
                @endif
            </div>
            <div class="table-responsive">
                <table id="export-table" class="table sale-list stripe" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{trans('file.Date')}}</th>
                            <th>{{trans('file.ID')}}</th>
                            <th>{{trans('file.Name')}}</th>
                            <th>{{trans('file.Phone Number')}}</th>
                            <th>{{trans('file.Address')}}</th>
                            <th>{{trans('file.grand total')}}</th>
                            <th>{{trans('file.City')}}</th>
                            <th>{{trans('file.Products')}}</th>
                            <th>{{trans('file.Remark')}}</th>
                            <th>{{trans('file.Qty')}}</th>
                            <th class="not-exported">{{trans('file.Status')}}</th>  
                            <th class="not-exported">{{trans('file.Delivery Status')}}</th>
                        </tr>
                    </thead>
                    
                    <tfoot class="tfoot active">
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align: right;">{{trans('file.Total')}}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>                
                    </tfoot>
                </table>
            </div>
        </div>
    </div>    
</section>

<div id="sale-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="container mt-3 pb-2 border-bottom">
                <div class="row">
                    <div class="col-md-12">
                        <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">{{$general_setting->site_title}}<button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button></h3>
                    </div>
                    <div class="col-md-12 text-center">
                        <i style="font-size: 15px;">{{trans('file.Sale Details')}}</i>
                    </div>
                </div>
            </div>
            <div id="sale-content" class="modal-body">
            </div>
            <br>
            <table class="table table-bordered product-sale-list">
                <thead>
                    <th>#</th>
                    <th>{{trans('file.product')}}</th>                 
                    <th>{{trans('file.Qty')}}</th>
                    <th>{{trans('file.Unit Price')}}</th>
                    <th>{{trans('file.Subtotal')}}</th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="sale-footer" class="modal-body"></div>
        </div>
    </div>
</div>

<div id="add-delivery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Add Delivery')}}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'delivery.store', 'method' => 'post', 'files' => true, 'class' => 'delivery-form']) !!}
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Delivery Reference')}} *</strong></label>
                        <p id="dr"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Sale Reference')}} *</strong></label>
                        <p id="sr"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label><strong>{{trans('file.Status')}} *</strong></label>
                        <select required name="status" id="status" class="selectpicker form-control" data-live-search="true" title="Select status...">
                            <option value="1">{{trans('file.Pickup')}}</option>
                            <option value="2">{{trans('file.Sent')}}</option>
                            <option value="3">{{trans('file.Distribution')}}</option>
                            <option value="4">{{trans('file.Livré')}}</option>
                            <option value="5">{{trans('file.Ne répond pas')}}</option>
                            <option value="6">{{trans('file.Injoignable')}}</option>
                            <option value="7">{{trans('file.Erreur numéro')}}</option>
                            <option value="8">{{trans('file.Reporté')}}</option>
                            <option value="9">{{trans('file.Programmé')}}</option>
                            <option value="10">{{trans('file.Annulé')}}</option>
                            <option value="11">{{trans('file.Refusé')}}</option>
                            <option value="12">{{trans('file.Retourné')}}</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label><strong>{{trans('file.Date')}} *</strong></label>
                        <div class="input-group" >
                            <input type="text" class="form-control" id="dtpicker_delivery" name="status_date" required readonly>
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-3 form-group">
                        <label><strong>{{trans('file.Date')}} *</strong></label>
                        <div class="input-group date" id="dtpicker_delivery">
                            <input type="text" class="form-control" name="status_date" required readonly>
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Delivered By')}}</strong></label>
                        <input type="text" name="delivered_by" class="form-control">
                    </div>                                            
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.customer')}} *</strong></label>
                        <p id="customer"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Note')}}</strong></label>
                        <textarea rows="3" name="note" class="form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="reference_no">
                <input type="hidden" name="sale_id">
                <input type="hidden" name="is_close" value="0">
                <input type="hidden" name="returned" value="0">
                <input type="hidden" name="to_redirect" value="sales">
                <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $("ul#sale").siblings('a').attr('aria-expanded','true');
    $("ul#sale").addClass("show");
    $("ul#sale #export-menu").addClass("active");
    var public_key = <?php echo json_encode($lims_pos_setting_data->stripe_public_key) ?>;
    var all_permission = <?php echo json_encode($all_permission) ?>;
    var sale_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        // $(".buttons-csv").click(function() {
        //     $.LoadingOverlay("show");
        // });
    });

    $(".daterangepicker-field").daterangepicker({
      callback: function(startDate, endDate, period){
        var starting_date = startDate.format('YYYY-MM-DD');
        var ending_date = endDate.format('YYYY-MM-DD');
        var title = starting_date + ' To ' + ending_date;
        $(this).val(title);
        $('input[name="starting_date"]').val(starting_date);
        $('input[name="ending_date"]').val(ending_date);
      }
    });

    $('.selectpicker').selectpicker('refresh');

    $(document).on("click", "tr.sale-link td:not(:first-child, :last-child)", function() {
        var sale = $(this).parent().data('sale');
        saleDetails(sale);
    });

    $(document).on("click", ".view", function(){
        var sale = $(this).parent().parent().parent().parent().parent().data('sale');
        saleDetails(sale);
    });

    $("#print-btn").on("click", function(){
          var divToPrint=document.getElementById('sale-details');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},10);
    });

    $(document).on("click", "table.sale-list tbody .add-delivery", function(event) {
        var id = $(this).data('id').toString();
        $.get('delivery/create/'+id, function(data) {
            $('#dr').text(data[0]);
            $('#sr').text(data[1]);
            $('input[name="delivered_by"]').val(data[6]);
            $('#customer').html(data[2] + "<br>" + data[3] + "<br>" + data[4] + "<br>" + data[5]);
            $('textarea[name="note"]').val(data[7]);
            $('input[name="status_date"]').val(data[8]);
            $('input[name="reference_no"]').val(data[0]);
            $('input[name="sale_id"]').val(id);            
            $('#add-delivery').modal('show');
        });
    });

    $('select[name="status"]').on("change", function() {
        var id = $(this).val();
        if(id == "4")
            $('input[name="is_close"]').val("1");
        else {
            $('input[name="is_close"]').val("0");
        }
        if(id >= "10" && id >= "12")
            $('input[name="returned"]').val("1");
        else {
            $('input[name="returned"]').val("0");
        }
    });

    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var status = urlParams.get('status_id');
    $('select[name=status_id]').val(status);
    $('.selectpicker').selectpicker('refresh');
    var searchstring = urlParams.get('search_string');
    $('input[name=search_string]').val(searchstring);

    var starting_date = $("input[name=starting_date]").val(); 
    var ending_date = $("input[name=ending_date]").val();
    var status_id = $("#status_id").val();
    var search_string = $("#search_string").val();

    var tableExport = $('#export-table').DataTable( {
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ajax":{
            url:"./export-data",
            data:{
                all_permission: all_permission,
                starting_date: starting_date,
                ending_date: ending_date,
                status_id: status_id,
                search_string: search_string
            },
            dataType: "json",
            type:"post"
        },
        "createdRow": function( row, data, dataIndex ) {
            //alert(data['sale']);
            $(row).addClass('sale-link');
            $(row).attr('data-sale', data['sale']);
        },
        "columns": [
            {"data": "key"},
            {"data": "date"},
            {"data": "reference_no"},
            {"data": "customer"},
            {"data": "phone"},
            {"data": "address"},
            {"data": "grand_total"},
            {"data": "city"},
            {"data": "products"},
            {"data": "notes"},
            {"data": "qty"},
            {"data": "valide_status"},
            {"data": "delivery_status"}
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
            {
                "orderable": false,
                'targets': [0, 5, 8, 9, 10, 12],
            },
            // {
            //     'targets': 3,
            //     className: 'noVis'
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
            {
                text: '{{trans("file.Send Delivery")}}',
                className: 'buttons-csv',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        sale_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                var sale = $(this).closest('tr').data('sale');
                                sale_id[i-1] = sale[10];
                            }
                        });
                        if(sale_id.length && confirm("Are you sure want to send to delivery?")) {
                            $.LoadingOverlay("show");
                            $.ajax({
                                type:'POST',
                                url:'./senddeliverybyselection',
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
                extend: 'excel',
                text: '{{trans("file.Excel")}}',
                className: 'buttons-export',
                filename: function() {
                    var date = moment();
                    var currentDate = date.format('D-MM-YYYY');
                    return 'Export-' + currentDate;
                },
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: {
                        selected: true
                    }
                },
            },
            {
                text: '{{trans("file.Cancel Export")}}',
                className: 'buttons-cancel-export',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        sale_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                var sale = $(this).closest('tr').data('sale');
                                sale_id[i-1] = sale[10];
                            }
                        });
                        if(sale_id.length && confirm("Are you sure want to cancel export?")) {
                            $.ajax({
                                type:'POST',
                                url:'./returnexportbyselection',
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

            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    function saleDetails(sale){
        //alert(sale[9]);
        if(sale[9] == 1) //is_valide
            var valide_status = '<div class="badge badge-success">{{trans("file.Confirmed")}}</div>';
        else
            var valide_status = '<div class="badge badge-warning">{{trans("file.Not Confirmed")}}</div>';

        if (sale[7] == 1) 
            var sale_delivery = '<div class="badge badge-warning">Pickup<br>'+sale[20]+'</div>';
        else if (sale[7] == 2)
            var sale_delivery = '<div class="badge badge-info">Sent<br>'+sale[20]+'</div>';
        else if (sale[7] == 3)
            var sale_delivery = '<div class="badge badge-primary">Distribution<br>'+sale[20]+'</div>';
        else if (sale[7] == 4)
            var sale_delivery = '<div class="badge badge-success">Delivered<br>'+sale[20]+'</div>';
        else if (sale[7] == 5)
            var sale_delivery = '<div class="badge badge-danger">Ne répond pas<br>'+sale[20]+'</div>';
        else if (sale[7] == 6)
            var sale_delivery = '<div class="badge badge-danger">Injoignable<br>'+sale[20]+'</div>';
        else if (sale[7] == 7)
            var sale_delivery = '<div class="badge badge-danger">Erreur numéro<br>'+sale[20]+'</div>';
        else if (sale[7] == 8)
            var sale_delivery = '<div class="badge badge-danger">reporté<br>'+sale[20]+'</div>';
        else if (sale[7] == 9)
            var sale_delivery = '<div class="badge badge-danger">Programmé<br>'+sale[20]+'</div>';
        else if (sale[7] == 10)
            var sale_delivery = '<div class="badge badge-danger">Annulé<br>'+sale[20]+'</div>';
        else if (sale[7] == 11)
            var sale_delivery = '<div class="badge badge-danger">Refusé<br>'+sale[20]+'</div>';
        else if (sale[7] == 12)
            var sale_delivery = '<div class="badge badge-danger">Retourné<br>'+sale[20]+'</div>';
        else
            var sale_delivery = '<div class="badge badge-secondary">{{trans("file.Pas de livraison")}}</div>';
            
        var customer_add = sale[5].split("<br><br>").join("<br>");
        var sale_note = sale[13].split("<br><br>").join("<br>");
        var sale_staff = sale[14].split("<br><br>").join("<br>");
            
        var htmltext = '<div class="row"><div class="col-md-6"><table><tr><td><u><strong>{{trans("file.Date")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[0]+'</tr><tr><td><u><strong>{{trans("file.reference")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[1]+'</tr></table></div><div class="col-md-6"><table><tr><td><u><strong>{{trans("file.Status")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+valide_status+'</tr><tr><td><u><strong>{{trans("file.delivery")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale_delivery+'</td></tr></table></div></div><br><br><div class="row"><div class="col-md-12"><u><strong>{{trans("file.customer")}}</strong></u> : <br>'+sale[3]+'<br>'+sale[4]+'<br>'+customer_add+'<br>'+sale[6]+'</div></div>';
        //var htmltext = '<div class="row"><div class="col-md-6"><table><tr><td><u><strong>{{trans("file.Date")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[0]+'</tr><tr><td><u><strong>{{trans("file.reference")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[1]+'</tr></table></div><div class="col-md-6"><table><tr><td><u><strong>{{trans("file.Status")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+valide_status+'</tr><tr><td><u><strong>{{trans("file.delivery")}}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale_delivery+'</td></tr></table></div></div><br><br><div class="row"><div class="col-md-12"><u><strong>{{trans("file.customer")}}</strong></u> : <br>'+sale[3]+'<br>'+sale[4]+'<br>'+sale[5]+'<br>'+sale[6]+'</div></div>';
        
        $.get('./product_sale/' + sale[10], function(data){
            $(".product-sale-list tbody").remove();
            var name_code = data[0];
            var qty = data[1];
            var subtotal = data[6];
            var livraison = data[8];
            var newBody = $("<tbody>");
            $.each(name_code, function(index){
                var newRow = $("<tr>");
                var cols = '';
                cols += '<td><strong>' + (index+1) + '</strong></td>';
                cols += '<td>' + name_code[index] + '</td>';
                cols += '<td>' + qty[index] + '</td>';
                cols += '<td>' + parseFloat(subtotal[index] / qty[index]).toFixed(2) + '</td>';
                cols += '<td>' + parseFloat(subtotal[index]).toFixed(2) + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=4><strong>{{trans("file.grand total")}}:</strong></td>';
            cols += '<td>' + parseFloat(sale[12]).toFixed(2) + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            $("table.product-sale-list").append(newBody);
        });

        var htmlfooter = '<div class="row"><div class="col-md-6"><u><strong>{{trans("file.Sale Note")}}:</strong></u></div><div class="col-md-6"><u><strong>{{trans("file.Staff Note")}}:</strong></u></div></div><div class="row"><div class="col-md-6">'+sale_note+'</div><div class="col-md-6">'+sale_staff+'</div></div>';
        $('#sale-content').html(htmltext);
        $('#sale-footer').html(htmlfooter);
        $('#sale-details').modal('show');
    }

    $(document).on('submit', '.payment-form', function(e) {
        if( $('input[name="paying_amount"]').val() < parseFloat($('#amount').val()) ) {
            alert('Paying amount cannot be bigger than recieved amount');
            $('input[name="amount"]').val('');
            $(".change").text(parseFloat( $('input[name="paying_amount"]').val() - $('#amount').val() ).toFixed(2));
            e.preventDefault();
        }
        else if( $('input[name="edit_paying_amount"]').val() < parseFloat($('input[name="edit_amount"]').val()) ) {
            alert('Paying amount cannot be bigger than recieved amount');
            $('input[name="edit_amount"]').val('');
            $(".change").text(parseFloat( $('input[name="edit_paying_amount"]').val() - $('input[name="edit_amount"]').val() ).toFixed(2));
            e.preventDefault();
        }
        
        $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', false);
    });
    
    if(all_permission.indexOf("sales-delete") == -1)
        $('.buttons-delete').addClass('d-none');

        function confirmDelete() {
            if (confirm("Are you sure want to delete?")) {
                return true;
            }
            return false;
        }

    function confirmPaymentDelete() {
        if (confirm("Are you sure want to delete? If you delete this money will be refunded.")) {
            return true;
        }
        return false;
    }

</script>
@endsection

@section('scripts')


@endsection