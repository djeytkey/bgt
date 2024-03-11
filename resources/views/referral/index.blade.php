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
                <h3 class="text-center">{{trans('file.Referral List')}}</h3>
            </div>
            {!! Form::open(['route' => 'referral.index', 'method' => 'get']) !!}
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
        <table id="referral-table" class="table referral-list stripe" style="width: 100%">
            <thead>
                <tr>
                    
                    <th class="not-exported"></th>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.Parrain')}}</th>
                    <th>{{trans('file.Nombre Vendeurs')}}</th>
                    <th>{{trans('file.Vendeurs')}}</th>
                    <th>{{trans('file.Invoice Number')}}</th>
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
                    <th></th>
                    <th></th>
                    <th style="text-align: right;">{{trans('file.Total')}}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>                
            </tfoot>
        </table>
    </div>
</section>

<script type="text/javascript">

    $("ul#referral").siblings('a').attr('aria-expanded','true');
    $("ul#referral").addClass("show");
    $("ul#referral #referral-list-menu").addClass("active");

    var all_permission = <?php echo json_encode($all_permission) ?>;
    var referral_id = [];
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

    $('#referral-table').DataTable( {
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ordering": false,
        "ajax":{
            url:"referral/referral-data",
            data:{
                all_permission: all_permission,
                search_string: search_string,
                status_id: status_id
            },
            dataType: "json",
            type:"post"
        },
        "createdRow": function( row, data, dataIndex ) {
            $(row).addClass('referral-link');
            $(row).attr('data-referral', data['referral']);
        },
        "columns": [
            {"data": "key"},
            {"data": "date"},
            {"data": "p_name"},
            {"data": "withdraw_total"},
            {"data": "withdraw_v_users"},
            {"data": "facture_no"},
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
            <?php if (in_array("referral-index-pay", $all_permission)) { ?>
            {
                text: '{{trans("file.Payée")}}',
                className: 'btn-success',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        referral_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                var referral = $(this).closest('tr').data('referral');
                                referral_id[i-1] = referral[2];
                            }
                        });
                        if(referral_id.length && confirm("Vous êtes sur de changer en Payée ?")) {
                            $.LoadingOverlay("show");
                            $.ajax({
                                type:'POST',
                                url:'referral/paidbyselection',
                                data:{
                                    referralIdArray: referral_id
                                },
                                success:function(data){
                                    //alert(data);
                                    //dt.rows({ page: 'current', selected: true }).deselect();
                                    //dt.rows({ page: 'current', selected: true }).remove().draw(false);
                                    location.reload();
                                }
                            });
                        }
                        else if(!referral_id.length)
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
                        referral_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                var referral = $(this).closest('tr').data('referral');
                                referral_id[i-1] = referral[2];
                            }
                        });
                        if(referral_id.length && confirm("Vous êtes sur de changer en Virement ?")) {
                            $.LoadingOverlay("show");
                            $.ajax({
                                type:'POST',
                                url:'referral/virementbyselection',
                                data:{
                                    referralIdArray: referral_id
                                },
                                success:function(data){
                                    //alert(data);
                                    //dt.rows({ page: 'current', selected: true }).deselect();
                                    //dt.rows({ page: 'current', selected: true }).remove().draw(false);
                                    location.reload();
                                }
                            });
                        }
                        else if(!referral_id.length)
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

            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

</script>
@endsection

@section('scripts')


@endsection