@extends('layout.main') @section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}
        </div>
    @endif
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}
        </div>
    @endif

    <section class="no-search">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header mt-2">
                    <h3 class="text-center">{{ trans('file.Delivery List') }}</h3>
                </div>
                {!! Form::open(['route' => 'delivery.index', 'method' => 'get']) !!}
                <div class="row no-mrl mb-3">
                    <div class="col-md-4 mt-3">
                        <div class="form-group row">
                            <label class="d-tc mt-2"><strong>{{ trans('file.Choose Your Date') }}</strong> &nbsp;</label>
                            <div class="d-tc">
                                <div class="input-group">
                                    <input type="text" class="daterangepicker-field form-control"
                                        value="{{ $starting_date }} To {{ $ending_date }}" required />
                                    <input type="hidden" name="starting_date" value="{{ $starting_date }}" />
                                    <input type="hidden" name="ending_date" value="{{ $ending_date }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mt-3">
                        <div class="form-group row">
                            <label class="d-tc mt-2"><strong>{{ trans('file.Search') }}</strong> &nbsp;</label>
                            <div class="d-tc">
                                <input type="text" name="search_string" id="search_string" class="form-control"
                                    placeholder="{{ trans('file.Type to search...') }}">
                                <p class="no-mb">
                                    <small>(reference, customer)</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mt-3">
                        <div class="form-group row">
                            <label class="d-tc mt-2"><strong>{{ trans('file.Choose Status') }}</strong> &nbsp;</label>
                            <div class="d-tc">
                                <select id="status_id" name="status_id" class="selectpicker form-control"
                                    data-live-search="true">
                                    <option value="0">{{ trans('file.All Status') }}</option>
                                    <option value="10">{{ trans('file.Annulé') }}</option>
                                    <option value="13">{{ trans('file.Annulé ( SUIVI )') }}</option>
                                    <option value="4">{{ trans('file.Livré') }}</option>
                                    <option value="3">{{ trans('file.Mise en distribution') }}</option>
                                    <option value="7">{{ trans('file.Erreur Numéro') }}</option>
                                    <option value="14">{{ trans('file.client intéressé') }}</option>
                                    <option value="15">{{ trans('file.En cours') }}</option>
                                    <option value="5">{{ trans('file.Pas de réponse + SMS') }}</option>
                                    <option value="16">{{ trans('file.Pas de reponse ( SUIVI )') }}</option>
                                    <option value="17">{{ trans('file.En Voyage') }}</option>
                                    <option value="18">{{ trans('file.Hors-zone') }}</option>
                                    <option value="1">{{ trans('file.Ramassé') }}</option>
                                    <option value="8">{{ trans('file.Reporté') }}</option>
                                    <option value="19">{{ trans('file.Reporté ( SUIVI )') }}</option>
                                    <option value="9">{{ trans('file.Programmé') }}</option>
                                    <option value="20">{{ trans('file.Reçu') }}</option>
                                    <option value="11">{{ trans('file.Refusé') }}</option>
                                    <option value="12">{{ trans('file.Retourné') }}</option>
                                    <option value="21">{{ trans('file.En retour par AMANA') }}</option>
                                    <option value="22">{{ trans('file.Reporté aujourd hui') }}</option>
                                    <option value="2">{{ trans('file.Expédié') }}</option>
                                    <option value="23">{{ trans('file.Expédier par AMANA') }}</option>
                                    <option value="6">{{ trans('file.Injoignable') }}</option>
                                    <option value="24">{{ trans('file.Injoignable ( SUIVI )') }}</option>
                                    <option value="25">{{ trans('file.Boite Vocal') }}</option>
                                    <option value="26">{{ trans('file.Boite Vocal ( SUIVI )') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 mt-3">
                        <div class="form-group">
                            <button class="btn btn-primary" id="filter-btn"
                                type="submit">{{ trans('file.submit') }}</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <a href="{{ route('sales.index') }}" class="btn btn-success"><i class="dripicons-list"></i>
                {{ trans('file.Sales List') }}</a>&nbsp;
            @if (Auth::user()->role_id < 2)
                <a href="{{ url('sales/export') }}" class="btn btn-primary"><i class="dripicons-list"></i>
                    {{ trans('file.Export List') }}</a>
            @endif
        </div>
        <div class="table-responsive">
            <table id="delivery-table" class="table sale-list stripe" style="width: 100%">
                <thead>
                    <tr>
                        <th class="not-exported"></th>
                        <th>{{ trans('file.Delivery Reference') }}</th>
                        <th>{{ trans('file.Sale Reference') }}</th>
                        <th>{{ trans('file.Customer') }}</th>
                        <th>{{ trans('file.Phone Number') }}</th>
                        <th>{{ trans('file.Address') }}</th>
                        <th>{{ trans('file.Products') }}</th>
                        <th>{{ trans('file.City') }}</th>
                        <th>{{ trans('file.grand total') }}</th>
                        <th class="not-exported">{{ trans('file.Facturation') }}</th>
                        <th class="not-exported">{{ trans('file.API') }}</th>
                        <th>{{ trans('file.Status') }}</th>
                        <th>{{ trans('file.action') }}</th>
                    </tr>
                </thead>

                <tfoot class="tfoot active">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align: right;">{{ trans('file.Total') }}</th>
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

    <div id="delivery-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="container mt-3 pb-2 border-bottom">
                    <div class="row">
                        <div class="col-md-3">
                            <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i
                                    class="dripicons-print"></i> {{ trans('file.Print') }}</button>

                            {{ Form::open(['route' => 'delivery.sendMail', 'method' => 'post', 'class' => 'sendmail-form']) }}
                            <input type="hidden" name="delivery_id">
                            <button class="btn btn-default btn-sm d-print-none"><i class="dripicons-mail"></i>
                                {{ trans('file.Email') }}</button>
                            {{ Form::close() }}
                        </div>
                        <div class="col-md-6">
                            <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">
                                <img src="{{ url('public/logo', $general_setting->site_logo) }}" width="30">
                                {{ $general_setting->site_title }}
                            </h3>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close"
                                class="close d-print-none"><span aria-hidden="true"><i
                                        class="dripicons-cross"></i></span></button>
                        </div>
                        <div class="col-md-12 text-center">
                            <i style="font-size: 15px;">{{ trans('file.Delivery Details') }}</i>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <table class="table modal-table table-bordered" id="delivery-content">
                        <tbody></tbody>
                    </table>
                    <br>
                    <table class="table modal-table table-bordered delivery-status-list">
                        <tbody></tbody>
                    </table>
                    <br>
                    <table class="table modal-table table-bordered product-delivery-list">
                        <thead>
                            <th>No</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Qty</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div id="delivery-footer" class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="view-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.All') }} {{ trans('file.Payment') }}
                    </h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover payment-list">
                        <thead>
                            <tr>
                                <th>{{ trans('file.date') }}</th>
                                <th>{{ trans('file.reference') }}</th>
                                <th>{{ trans('file.Account') }}</th>
                                <th>{{ trans('file.Amount') }}</th>
                                <th>{{ trans('file.Paid By') }}</th>
                                <th>{{ trans('file.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="add-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Payment') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'sale.add-payment', 'method' => 'post', 'files' => true, 'class' => 'payment-form']) !!}
                    <div class="row">
                        <input type="hidden" name="balance">
                        <div class="col-md-6">
                            <label>{{ trans('file.Recieved Amount') }} *</label>
                            <input type="text" name="paying_amount" class="form-control numkey" step="any"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label>{{ trans('file.Paying Amount') }} *</label>
                            <input type="text" id="amount" name="amount" class="form-control" step="any"
                                required>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label>{{ trans('file.Change') }} : </label>
                            <p class="change ml-2">0.00</p>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label>{{ trans('file.Paid By') }}</label>
                            <select name="paid_by_id" class="form-control">
                                <option value="1">Cash</option>
                                <option value="2">Gift Card</option>
                                <option value="3">Credit Card</option>
                                <option value="4">Cheque</option>
                                <option value="5">Paypal</option>
                                <option value="6">Deposit</option>
                            </select>
                        </div>
                    </div>
                    <div class="gift-card form-group">
                        <label> {{ trans('file.Gift Card') }} *</label>
                        <select id="gift_card_id" name="gift_card_id" class="selectpicker form-control"
                            data-live-search="true" title="Select Gift Card...">
                            @php
                                $balance = [];
                                $expired_date = [];
                            @endphp
                            @foreach ($lims_gift_card_list as $gift_card)
                                <?php
                                $balance[$gift_card->id] = $gift_card->amount - $gift_card->expense;
                                $expired_date[$gift_card->id] = $gift_card->expired_date;
                                ?>
                                <option value="{{ $gift_card->id }}">{{ $gift_card->card_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <div class="card-element" class="form-control">
                        </div>
                        <div class="card-errors" role="alert"></div>
                    </div>
                    <div id="cheque">
                        <div class="form-group">
                            <label>{{ trans('file.Cheque Number') }} *</label>
                            <input type="text" name="cheque_no" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label> {{ trans('file.Account') }}</label>
                        <select class="form-control selectpicker" name="account_id">
                            @foreach ($lims_account_list as $account)
                                @if ($account->is_default)
                                    <option selected value="{{ $account->id }}">{{ $account->name }}
                                        [{{ $account->account_no }}]</option>
                                @else
                                    <option value="{{ $account->id }}">{{ $account->name }}
                                        [{{ $account->account_no }}]</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('file.Payment Note') }}</label>
                        <textarea rows="3" class="form-control" name="payment_note"></textarea>
                    </div>

                    <input type="hidden" name="sale_id">

                    <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div id="edit-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Update Payment') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'sale.update-payment', 'method' => 'post', 'class' => 'payment-form']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <label>{{ trans('file.Recieved Amount') }} *</label>
                            <input type="text" name="edit_paying_amount" class="form-control numkey" step="any"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label>{{ trans('file.Paying Amount') }} *</label>
                            <input type="text" name="edit_amount" class="form-control" step="any" required>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label>{{ trans('file.Change') }} : </label>
                            <p class="change ml-2">0.00</p>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label>{{ trans('file.Paid By') }}</label>
                            <select name="edit_paid_by_id" class="form-control selectpicker">
                                <option value="1">Cash</option>
                                <option value="2">Gift Card</option>
                                <option value="3">Credit Card</option>
                                <option value="4">Cheque</option>
                                <option value="5">Paypal</option>
                                <option value="6">Deposit</option>
                            </select>
                        </div>
                    </div>
                    <div class="gift-card form-group">
                        <label> {{ trans('file.Gift Card') }} *</label>
                        <select id="gift_card_id" name="gift_card_id" class="selectpicker form-control"
                            data-live-search="true" title="Select Gift Card...">
                            @foreach ($lims_gift_card_list as $gift_card)
                                <option value="{{ $gift_card->id }}">{{ $gift_card->card_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <div class="card-element" class="form-control">
                        </div>
                        <div class="card-errors" role="alert"></div>
                    </div>
                    <div id="edit-cheque">
                        <div class="form-group">
                            <label>{{ trans('file.Cheque Number') }} *</label>
                            <input type="text" name="edit_cheque_no" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label> {{ trans('file.Account') }}</label>
                        <select class="form-control selectpicker" name="account_id">
                            @foreach ($lims_account_list as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} [{{ $account->account_no }}]
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('file.Payment Note') }}</label>
                        <textarea rows="3" class="form-control" name="edit_payment_note"></textarea>
                    </div>

                    <input type="hidden" name="payment_id">

                    <button type="submit" class="btn btn-primary">{{ trans('file.update') }}</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div id="add-delivery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Delivery') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'delivery.store', 'method' => 'post', 'files' => true, 'class' => 'delivery-form']) !!}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label><strong>{{ trans('file.Delivery Reference') }} *</strong></label>
                            <p id="dr"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label><strong>{{ trans('file.Sale Reference') }} *</strong></label>
                            <p id="sr"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label><strong>{{ trans('file.Status') }} *</strong></label>
                            <select required name="status" id="status" class="selectpicker form-control"
                                data-live-search="true" title="Select status...">
                                <option value="1">{{ trans('file.Pickup') }}</option>
                                <option value="2">{{ trans('file.Sent') }}</option>
                                <option value="3">{{ trans('file.Distribution') }}</option>
                                <option value="4">{{ trans('file.Livré') }}</option>
                                <option value="5">{{ trans('file.Ne répond pas') }}</option>
                                <option value="6">{{ trans('file.Injoignable') }}</option>
                                <option value="7">{{ trans('file.Erreur numéro') }}</option>
                                <option value="8">{{ trans('file.Reporté') }}</option>
                                <option value="9">{{ trans('file.Programmé') }}</option>
                                <option value="10">{{ trans('file.Annulé') }}</option>
                                <option value="11">{{ trans('file.Refusé') }}</option>
                                <option value="12">{{ trans('file.Retourné') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label><strong>{{ trans('file.Date') }} *</strong></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="dtpicker_delivery" name="status_date"
                                    required readonly>
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
                            <label><strong>{{ trans('file.Delivered By') }}</strong></label>
                            <input type="text" name="delivered_by" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label><strong>{{ trans('file.customer') }} *</strong></label>
                            <p id="customer"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label><strong>{{ trans('file.Note') }}</strong></label>
                            <textarea rows="3" name="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="reference_no">
                    <input type="hidden" name="sale_id">
                    <input type="hidden" name="is_close" value="0">
                    <input type="hidden" name="returned" value="0">
                    <input type="hidden" name="to_redirect" value="delivery">
                    <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $("ul#sale").siblings('a').attr('aria-expanded', 'true');
        $("ul#sale").addClass("show");
        $("ul#sale #delivery-menu").addClass("active");

        var public_key = <?php echo json_encode($lims_pos_setting_data->stripe_public_key); ?>;
        var all_permission = <?php echo json_encode($all_permission); ?>;
        var sale_id = [];
        var user_verified = <?php echo json_encode(env('USER_VERIFIED')); ?>;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            if (jQuery.inArray("isAdmin", all_permission) == -1) {
                $(".buttons-export").each(function() {
                    $(this).remove();
                });
            }
        });

        $(".daterangepicker-field").daterangepicker({
            callback: function(startDate, endDate, period) {
                var starting_date = startDate.format('YYYY-MM-DD');
                var ending_date = endDate.format('YYYY-MM-DD');
                var title = starting_date + ' To ' + ending_date;
                $(this).val(title);
                $('input[name="starting_date"]').val(starting_date);
                $('input[name="ending_date"]').val(ending_date);
            }
        });

        $('.selectpicker').selectpicker('refresh');

        var balance = <?php echo json_encode($balance); ?>;
        var expired_date = <?php echo json_encode($expired_date); ?>;
        var current_date = <?php echo json_encode(date('Y-m-d')); ?>;
        var payment_date = [];
        var payment_reference = [];
        var paid_amount = [];
        var paying_method = [];
        var payment_id = [];
        var payment_note = [];
        var account = [];
        var deposit;

        $(".gift-card").hide();
        $(".card-element").hide();
        $("#cheque").hide();
        $('#view-payment').modal('hide');

        // $(document).on("click", "tr.delivery-link td:not(:first-child, :last-child)", function() {
        //     var delivery = $(this).parent().data('delivery');
        //     // alert(delivery);
        //     deliveryDetails(delivery);
        // });

        $(document).on("click", ".view", function() {
            var delivery = $(this).parent().parent().parent().parent().parent().data('delivery');
            //alert(delivery);
            deliveryDetails(delivery);
        });

        $("#print-btn").on("click", function() {
            var divToPrint = document.getElementById('sale-details');
            var newWin = window.open('', 'Print-Window');
            newWin.document.open();
            newWin.document.write(
                '<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css'); ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">' +
                divToPrint.innerHTML + '</body>');
            newWin.document.close();
            setTimeout(function() {
                newWin.close();
            }, 10);
        });

        $(document).on("click", "table.sale-list tbody .add-payment", function() {
            $("#cheque").hide();
            $(".gift-card").hide();
            $(".card-element").hide();
            $('select[name="paid_by_id"]').val(1);
            $('.selectpicker').selectpicker('refresh');
            rowindex = $(this).closest('tr').index();
            deposit = $('table.sale-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.deposit').val();
            var sale_id = $(this).data('id').toString();
            var balance = $('table.sale-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(11)')
                .text();
            balance = parseFloat(balance.replace(/,/g, ''));
            $('input[name="paying_amount"]').val(balance);
            $('#add-payment input[name="balance"]').val(balance);
            $('input[name="amount"]').val(balance);
            $('input[name="sale_id"]').val(sale_id);
        });

        $(document).on("click", "table.sale-list tbody .get-payment", function(event) {
            rowindex = $(this).closest('tr').index();
            deposit = $('table.sale-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.deposit').val();
            var id = $(this).data('id').toString();
            $.get('sales/getpayment/' + id, function(data) {
                $(".payment-list tbody").remove();
                var newBody = $("<tbody>");
                payment_date = data[0];
                payment_reference = data[1];
                paid_amount = data[2];
                paying_method = data[3];
                payment_id = data[4];
                payment_note = data[5];
                cheque_no = data[6];
                gift_card_id = data[7];
                change = data[8];
                paying_amount = data[9];
                account_name = data[10];
                account_id = data[11];

                $.each(payment_date, function(index) {
                    var newRow = $("<tr>");
                    var cols = '';

                    cols += '<td>' + payment_date[index] + '</td>';
                    cols += '<td>' + payment_reference[index] + '</td>';
                    cols += '<td>' + account_name[index] + '</td>';
                    cols += '<td>' + paid_amount[index] + '</td>';
                    cols += '<td>' + paying_method[index] + '</td>';
                    if (paying_method[index] != 'Paypal')
                        cols +=
                        '<td><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('file.action') }}<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu"><li><button type="button" class="btn btn-link edit-btn" data-id="' +
                        payment_id[index] +
                        '" data-clicked=false data-toggle="modal" data-target="#edit-payment"><i class="dripicons-document-edit"></i> {{ trans('file.edit') }}</button></li><li class="divider"></li>{{ Form::open(['route' => 'sale.delete-payment', 'method' => 'post']) }}<li><input type="hidden" name="id" value="' +
                        payment_id[index] +
                        '" /> <button type="submit" class="btn btn-link" onclick="return confirmPaymentDelete()"><i class="dripicons-trash"></i> {{ trans('file.delete') }}</button></li>{{ Form::close() }}</ul></div></td>';
                    else
                        cols +=
                        '<td><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('file.action') }}<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">{{ Form::open(['route' => 'sale.delete-payment', 'method' => 'post']) }}<li><input type="hidden" name="id" value="' +
                        payment_id[index] +
                        '" /> <button type="submit" class="btn btn-link" onclick="return confirmPaymentDelete()"><i class="dripicons-trash"></i> {{ trans('file.delete') }}</button></li>{{ Form::close() }}</ul></div></td>';

                    newRow.append(cols);
                    newBody.append(newRow);
                    $("table.payment-list").append(newBody);
                });
                $('#view-payment').modal('show');
            });
        });

        $("table.payment-list").on("click", ".edit-btn", function(event) {
            $(".edit-btn").attr('data-clicked', true);
            $(".card-element").hide();
            $("#edit-cheque").hide();
            $('.gift-card').hide();
            $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', false);
            var id = $(this).data('id').toString();
            $.each(payment_id, function(index) {
                if (payment_id[index] == parseFloat(id)) {
                    $('input[name="payment_id"]').val(payment_id[index]);
                    $('#edit-payment select[name="account_id"]').val(account_id[index]);
                    if (paying_method[index] == 'Cash')
                        $('select[name="edit_paid_by_id"]').val(1);
                    else if (paying_method[index] == 'Gift Card') {
                        $('select[name="edit_paid_by_id"]').val(2);
                        $('#edit-payment select[name="gift_card_id"]').val(gift_card_id[index]);
                        $('.gift-card').show();
                        $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', true);
                    } else if (paying_method[index] == 'Credit Card') {
                        $('select[name="edit_paid_by_id"]').val(3);
                        $.getScript("public/vendor/stripe/checkout.js");
                        $(".card-element").show();
                        $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', true);
                    } else if (paying_method[index] == 'Cheque') {
                        $('select[name="edit_paid_by_id"]').val(4);
                        $("#edit-cheque").show();
                        $('input[name="edit_cheque_no"]').val(cheque_no[index]);
                        $('input[name="edit_cheque_no"]').attr('required', true);
                    } else
                        $('select[name="edit_paid_by_id"]').val(6);

                    $('.selectpicker').selectpicker('refresh');
                    $("#payment_reference").html(payment_reference[index]);
                    $('input[name="edit_paying_amount"]').val(paying_amount[index]);
                    $('#edit-payment .change').text(change[index]);
                    $('input[name="edit_amount"]').val(paid_amount[index]);
                    $('textarea[name="edit_payment_note"]').val(payment_note[index]);
                    return false;
                }
            });
            $('#view-payment').modal('hide');
        });

        $('select[name="paid_by_id"]').on("change", function() {
            var id = $(this).val();
            $('input[name="cheque_no"]').attr('required', false);
            $('#add-payment select[name="gift_card_id"]').attr('required', false);
            $(".payment-form").off("submit");
            if (id == 2) {
                $(".gift-card").show();
                $(".card-element").hide();
                $("#cheque").hide();
                $('#add-payment select[name="gift_card_id"]').attr('required', true);
            } else if (id == 3) {
                $.getScript("public/vendor/stripe/checkout.js");
                $(".card-element").show();
                $(".gift-card").hide();
                $("#cheque").hide();
            } else if (id == 4) {
                $("#cheque").show();
                $(".gift-card").hide();
                $(".card-element").hide();
                $('input[name="cheque_no"]').attr('required', true);
            } else if (id == 5) {
                $(".card-element").hide();
                $(".gift-card").hide();
                $("#cheque").hide();
            } else {
                $(".card-element").hide();
                $(".gift-card").hide();
                $("#cheque").hide();
                if (id == 6) {
                    if ($('#add-payment input[name="amount"]').val() > parseFloat(deposit))
                        alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
                }
            }
        });

        $('#add-payment select[name="gift_card_id"]').on("change", function() {
            var id = $(this).val();
            if (expired_date[id] < current_date)
                alert('This card is expired!');
            else if ($('#add-payment input[name="amount"]').val() > balance[id]) {
                alert('Amount exceeds card balance! Gift Card balance: ' + balance[id]);
            }
        });

        $('input[name="paying_amount"]').on("input", function() {
            $(".change").text(parseFloat($(this).val() - $('input[name="amount"]').val()).toFixed(2));
        });

        $('input[name="amount"]').on("input", function() {
            if ($(this).val() > parseFloat($('input[name="paying_amount"]').val())) {
                alert('Paying amount cannot be bigger than recieved amount');
                $(this).val('');
            } else if ($(this).val() > parseFloat($('input[name="balance"]').val())) {
                alert('Paying amount cannot be bigger than due amount');
                $(this).val('');
            }
            $(".change").text(parseFloat($('input[name="paying_amount"]').val() - $(this).val()).toFixed(2));
            var id = $('#add-payment select[name="paid_by_id"]').val();
            var amount = $(this).val();
            if (id == 2) {
                id = $('#add-payment select[name="gift_card_id"]').val();
                if (amount > balance[id])
                    alert('Amount exceeds card balance! Gift Card balance: ' + balance[id]);
            } else if (id == 6) {
                if (amount > parseFloat(deposit))
                    alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
            }
        });

        $('select[name="edit_paid_by_id"]').on("change", function() {
            var id = $(this).val();
            $('input[name="edit_cheque_no"]').attr('required', false);
            $('#edit-payment select[name="gift_card_id"]').attr('required', false);
            $(".payment-form").off("submit");
            if (id == 2) {
                $(".card-element").hide();
                $("#edit-cheque").hide();
                $('.gift-card').show();
                $('#edit-payment select[name="gift_card_id"]').attr('required', true);
            } else if (id == 3) {
                $(".edit-btn").attr('data-clicked', true);
                $.getScript("public/vendor/stripe/checkout.js");
                $(".card-element").show();
                $("#edit-cheque").hide();
                $('.gift-card').hide();
            } else if (id == 4) {
                $("#edit-cheque").show();
                $(".card-element").hide();
                $('.gift-card').hide();
                $('input[name="edit_cheque_no"]').attr('required', true);
            } else {
                $(".card-element").hide();
                $("#edit-cheque").hide();
                $('.gift-card').hide();
                if (id == 6) {
                    if ($('input[name="edit_amount"]').val() > parseFloat(deposit))
                        alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
                }
            }
        });

        $('#edit-payment select[name="gift_card_id"]').on("change", function() {
            var id = $(this).val();
            if (expired_date[id] < current_date)
                alert('This card is expired!');
            else if ($('#edit-payment input[name="edit_amount"]').val() > balance[id])
                alert('Amount exceeds card balance! Gift Card balance: ' + balance[id]);
        });

        $('input[name="edit_paying_amount"]').on("input", function() {
            $(".change").text(parseFloat($(this).val() - $('input[name="edit_amount"]').val()).toFixed(2));
        });

        $('input[name="edit_amount"]').on("input", function() {
            if ($(this).val() > parseFloat($('input[name="edit_paying_amount"]').val())) {
                alert('Paying amount cannot be bigger than recieved amount');
                $(this).val('');
            }
            $(".change").text(parseFloat($('input[name="edit_paying_amount"]').val() - $(this).val()).toFixed(2));
            var amount = $(this).val();
            var id = $('#edit-payment select[name="gift_card_id"]').val();
            if (amount > balance[id]) {
                alert('Amount exceeds card balance! Gift Card balance: ' + balance[id]);
            }
            var id = $('#edit-payment select[name="edit_paid_by_id"]').val();
            if (id == 6) {
                if (amount > parseFloat(deposit))
                    alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
            }
        });

        $(document).on("click", "table.sale-list tbody .add-delivery", function(event) {
            var id = $(this).data('id').toString();
            $.get('delivery/create/' + id, function(data) {
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
            if (id == "4")
                $('input[name="is_close"]').val("1");
            else {
                $('input[name="is_close"]').val("0");
            }
            if (id >= "10" && id <= "12")
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

        $('#delivery-table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "ajax": {
                url: "delivery/delivery-data",
                data: {
                    all_permission: all_permission,
                    starting_date: starting_date,
                    ending_date: ending_date,
                    status_id: status_id,
                    search_string: search_string
                },
                dataType: "json",
                type: "post"
            },
            "createdRow": function(row, data, dataIndex) {
                $(row).addClass('delivery-link');
                $(row).attr('data-delivery', data['delivery']);
            },
            "columns": [{
                    "data": "key"
                },
                {
                    "data": "delivery_reference_no"
                },
                {
                    "data": "sale_reference_no"
                },
                {
                    "data": "customer_name"
                },
                {
                    "data": "customer_phone"
                },
                {
                    "data": "customer_address"
                },
                {
                    "data": "products"
                },
                {
                    "data": "customer_city"
                },
                {
                    "data": "grand_total"
                },
                {
                    "data": "facture"
                },
                {
                    "data": "delivery_api"
                },
                {
                    "data": "delivery_status"
                },
                {
                    "data": "options"
                },
            ],
            'language': {
                'lengthMenu': '_MENU_ {{ trans('file.records per page') }}',
                "info": '<small>{{ trans('file.Showing') }} _START_ - _END_ (_TOTAL_)</small>',
                "search": '{{ trans('file.Search') }}',
                'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
                }
            },
            //order:[['1', 'desc']],
            'columnDefs': [{
                    "orderable": false,
                    'targets': [0, 1, 5, 6, 8, 9, 10],
                },
                // {
                //     'targets': 3,
                //     className: 'noVis'
                // },
                {
                    'render': function(data, type, row, meta) {
                        if (type === 'display') {
                            data =
                                '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
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
            'select': {
                style: 'multi',
                selector: 'td:first-child'
            },
            'lengthMenu': [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: '<"row"lfB>rtip',
            buttons: [
                /*{
                    text: '{{ trans('file.Export') }}',
                    className: 'buttons-export',
                    action: function ( e, dt, node, config ) {
                        if(user_verified == '1') {
                            sale_id.length = 0;
                            $(':checkbox:checked').each(function(i){
                                if(i){
                                    var sale = $(this).closest('tr').data('sale');
                                    sale_id[i-1] = sale[10];
                                }
                            });
                            if(sale_id.length && confirm("Are you sure want to export?")) {
                                $.LoadingOverlay("show");
                                $.ajax({
                                    type:'POST',
                                    url:'sales/exportbyselection',
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
                },*/
                {
                    extend: 'colvis',
                    text: '{{ trans('file.Column visibility') }}',
                    //columns: ':not(.noVis)'
                    columns: ':gt(0)'
                },
            ],
            drawCallback: function() {
                var api = this.api();
                datatable_sum(api, false);
            }
        });

        function datatable_sum(dt_selector, is_calling_first) {
            if (dt_selector.rows('.selected').any() && is_calling_first) {
                var rows = dt_selector.rows('.selected').indexes();

                $(dt_selector.column(8).footer()).html(dt_selector.cells(rows, 8, {
                    page: 'current'
                }).data().sum().toFixed(2));
            } else {
                $(dt_selector.column(8).footer()).html(dt_selector.cells(rows, 8, {
                    page: 'current'
                }).data().sum().toFixed(2));
            }
        }

        function deliveryDetails(delivery) {
            // $('input[name="delivery_id"]').val(delivery[3]);
            $("#delivery-content tbody").remove();
            var newBody = $("<tbody>");
            var rows = status = '';
            rows += '<tr><td>Date</td><td>' + delivery[0] + '</td></tr>';
            rows += '<tr><td>Delivery Reference</td><td>' + delivery[1] + '</td></tr>';
            rows += '<tr><td>Sale Reference</td><td>' + delivery[2] + '</td></tr>';
            rows += '<tr><td>Customer Name</td><td>' + delivery[4] + '</td></tr>';
            rows += '<tr><td>Phone</td><td>' + delivery[5] + '</td></tr>';
            var customer_address = delivery[6].split("<br><br>").join("<br>");
            rows += '<tr><td>Address</td><td>' + customer_address + '<br>' + delivery[7] + '</td></tr>';
            var delivery_note = delivery[8].split("<br><br>").join("<br>");
            rows += '<tr><td>Note</td><td>' + delivery_note + '</td></tr>';

            newBody.append(rows);
            $("table#delivery-content").append(newBody);

            $.get('delivery/delivery_status/' + delivery[3], function(data) {
                $(".delivery-status-list tbody").remove();
                var status = data[0];
                var status_date = data[1];
                var comment = data[2];
                var newBody = $("<tbody>");
                $.each(status, function(index) {
                    var newRow = $("<tr>");
                    var cols = '';
                    switch (status[index]) {
                        case "1":
                            cols += '<td><div class="badge badge-warning">Ramassé</div></td>';
                            break;
                        case "2":
                            cols += '<td><div class="badge badge-info">Expédié</div></td>';
                            break;
                        case "3":
                            cols += '<td><div class="badge badge-primary">Mise en distribution</div></td>';
                            break;
                        case "4":
                            cols += '<td><div class="badge badge-success">Livré</div></td>';
                            break;
                        case "5":
                            cols += '<td><div class="badge badge-danger">Pas de réponse + SMS</div></td>';
                            break;
                        case "6":
                            cols += '<td><div class="badge badge-danger">Injoignable</div></td>';
                            break;
                        case "7":
                            cols += '<td><div class="badge badge-danger">Erreur numéro</div></td>';
                            break;
                        case "8":
                            cols += '<td><div class="badge badge-danger">Reporté</div></td>';
                            break;
                        case "9":
                            cols += '<td><div class="badge badge-danger">Programmé</div></td>';
                            break;
                        case "10":
                            cols += '<td><div class="badge badge-danger">Annulé</div></td>';
                            break;
                        case "11":
                            cols += '<td><div class="badge badge-danger">Refusé</div></td>';
                            break;
                        case "12":
                            cols += '<td><div class="badge badge-danger">Retourné</div></td>';
                            break;
                        case "13":
                            cols += '<td><div class="badge badge-danger">Annulé ( SUIVI )</div></td>';
                            break;
                        case "14":
                            cols += '<td><div class="badge badge-info">client intéressé</div></td>';
                            break;
                        case "15":
                            cols += '<td><div class="badge badge-info">En cours</div></td>';
                            break;
                        case "16":
                            cols += '<td><div class="badge badge-danger">Pas de reponse ( SUIVI )</div></td>';
                            break;
                        case "17":
                            cols += '<td><div class="badge badge-warning">En Voyage</div></td>';
                            break;
                        case "18":
                            cols += '<td><div class="badge badge-danger">Hors-zone</div></td>';
                            break;
                        case "19":
                            cols += '<td><div class="badge badge-danger">Reporté ( SUIVI )</div></td>';
                            break;
                        case "20":
                            cols += '<td><div class="badge badge-info">Reçu</div></td>';
                            break;
                        case "21":
                            cols += '<td><div class="badge badge-danger">En retour par AMANA</div></td>';
                            break;
                        case "22":
                            cols += '<td><div class="badge badge-danger">Reporté aujourd\'hui</div></td>';
                            break;
                        case "23":
                            cols += '<td><div class="badge badge-danger">Expédier par AMANA</div></td>';
                            break;
                        case "24":
                            cols += '<td><div class="badge badge-danger">Injoignable ( SUIVI )</div></td>';
                            break;
                        case "25":
                            cols += '<td>div class="badge badge-danger">Boite Vocal</div></td>';
                            break;
                        case "26":
                            cols += '<td><div class="badge badge-danger">Boite Vocal ( SUIVI )</div></td>';
                            break;
                        case "27":
                            cols += '<td><div class="badge badge-primary">Nouveau Colis</div></td>';
                            break;
                        case "28":
                            cols += '<td><div class="badge badge-primary">Attente De Ramassage</div></td>';
                            break;
                    }
                    
                    var trimed_comment = comment[index].trim();
                    var splited_comment = trimed_comment.split("Commentaire: ");
                    if (splited_comment.length == 2) {
                        var comment_to_show = splited_comment[1];
                    } else {
                        var last_comment = splited_comment[0].split(" | ");
                        if (last_comment.length == 2)
                            var comment_to_show = splited_comment[0].replace(" | ", "<br/>");
                        else    
                            var comment_to_show = splited_comment[0];
                    }

                    cols += '<td>' + status_date[index] + '<br /><b>' + comment_to_show + '</b></td>';
                    newRow.append(cols);
                    newBody.append(newRow);
                });
                $("table.delivery-status-list").append(newBody);
            });

            $.get('delivery/product_delivery/' + delivery[3], function(data) {
                $(".product-delivery-list tbody").remove();
                var code = data[0];
                var description = data[1];
                var qty = data[2];
                var newBody = $("<tbody>");
                $.each(code, function(index) {
                    var newRow = $("<tr>");
                    var cols = '';
                    cols += '<td><strong>' + (index + 1) + '</strong></td>';
                    cols += '<td>' + code[index] + '</td>';
                    cols += '<td>' + description[index] + '</td>';
                    cols += '<td>' + qty[index] + '</td>';
                    newRow.append(cols);
                    newBody.append(newRow);
                });
                $("table.product-delivery-list").append(newBody);
            });

            var htmlfooter = '<div class="col-md-6 form-group"><p>Delivered By: ' + delivery[10] + '</p></div>';
            // htmlfooter += '<div class="col-md-6 form-group"><img style="max-width:850px;height:100%;max-height:130px" src="data:image/png;base64,'+barcode+'" alt="barcode" /></div>';
            htmlfooter += '<br><br><br><br>';
            htmlfooter += '';

            //$('#delivery-footer').html(htmlfooter);
            $('#delivery-details').modal('show');
        }

        // function deliveryDetails(delivery){
        //     //alert(sale);
        //     if(sale[9] == 1) //is_valide
        //         var valide_status = '<div class="badge badge-success">{{ trans('file.Confirmed') }}</div>';
        //     else
        //         var valide_status = '<div class="badge badge-warning">{{ trans('file.Not Confirmed') }}</div>';

        //     if (sale[7] == 1) 
        //         var sale_delivery = '<div class="badge badge-warning">Pickup<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 2)
        //         var sale_delivery = '<div class="badge badge-info">Sent<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 3)
        //         var sale_delivery = '<div class="badge badge-primary">Distribution<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 4)
        //         var sale_delivery = '<div class="badge badge-success">Delivered<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 5)
        //         var sale_delivery = '<div class="badge badge-danger">Ne répond pas<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 6)
        //         var sale_delivery = '<div class="badge badge-danger">Injoignable<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 7)
        //         var sale_delivery = '<div class="badge badge-danger">Erreur numéro<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 8)
        //         var sale_delivery = '<div class="badge badge-danger">reporté<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 9)
        //         var sale_delivery = '<div class="badge badge-danger">Programmé<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 10)
        //         var sale_delivery = '<div class="badge badge-danger">Annulé<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 11)
        //         var sale_delivery = '<div class="badge badge-danger">Refusé<br>'+sale[20]+'</div>';
        //     else if (sale[7] == 12)
        //         var sale_delivery = '<div class="badge badge-danger">Retourné<br>'+sale[20]+'</div>';
        //     else
        //         var sale_delivery = '<div class="badge badge-secondary">{{ trans('file.Pas de livraison') }}</div>';

        //     var customer_add = sale[5].split("<br><br>").join("<br>");
        //     var sale_note = sale[13].split("<br><br>").join("<br>");
        //     var sale_staff = sale[14].split("<br><br>").join("<br>");

        //     var htmltext = '<div class="row"><div class="col-md-6"><table><tr><td><u><strong>{{ trans('file.Date') }}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[0]+'</tr><tr><td><u><strong>{{ trans('file.reference') }}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[1]+'</tr></table></div><div class="col-md-6"><table><tr><td><u><strong>{{ trans('file.Status') }}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+valide_status+'</tr><tr><td><u><strong>{{ trans('file.Delivery') }}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale_delivery+'</td></tr></table></div></div><br><br><div class="row"><div class="col-md-12"><u><strong>{{ trans('file.customer') }}</strong></u> : <br>'+sale[3]+'<br>'+sale[4]+'<br>'+customer_add+'<br>'+sale[6]+'</div></div>';
        //     //var htmltext = '<div class="row"><div class="col-md-6"><table><tr><td><u><strong>{{ trans('file.Date') }}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[0]+'</tr><tr><td><u><strong>{{ trans('file.reference') }}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale[1]+'</tr></table></div><div class="col-md-6"><table><tr><td><u><strong>{{ trans('file.Status') }}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+valide_status+'</tr><tr><td><u><strong>{{ trans('file.delivery') }}</strong></u></td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>'+sale_delivery+'</td></tr></table></div></div><br><br><div class="row"><div class="col-md-12"><u><strong>{{ trans('file.customer') }}</strong></u> : <br>'+sale[3]+'<br>'+sale[4]+'<br>'+sale[5]+'<br>'+sale[6]+'</div></div>';

        //     $.get('sales/product_sale/' + sale[10], function(data){
        //         $(".product-sale-list tbody").remove();
        //         var name_code = data[0];
        //         var qty = data[1];
        //         var subtotal = data[6];
        //         var livraison = data[8];
        //         var newBody = $("<tbody>");
        //         $.each(name_code, function(index){
        //             var newRow = $("<tr>");
        //             var cols = '';
        //             cols += '<td><strong>' + (index+1) + '</strong></td>';
        //             cols += '<td>' + name_code[index] + '</td>';
        //             cols += '<td>' + qty[index] + '</td>';
        //             cols += '<td>' + parseFloat(subtotal[index] / qty[index]).toFixed(2) + '</td>';
        //             cols += '<td>' + parseFloat(subtotal[index]).toFixed(2) + '</td>';
        //             newRow.append(cols);
        //             newBody.append(newRow);
        //         });

        //         var newRow = $("<tr>");
        //         cols = '';
        //         cols += '<td colspan=4><strong>{{ trans('file.grand total') }}:</strong></td>';
        //         cols += '<td>' + parseFloat(sale[12]).toFixed(2) + '</td>';
        //         newRow.append(cols);
        //         newBody.append(newRow);

        //         $("table.product-sale-list").append(newBody);
        //     });

        //     var htmlfooter = '<div class="row"><div class="col-md-6"><u><strong>{{ trans('file.Sale Note') }}:</strong></u></div><div class="col-md-6"><u><strong>{{ trans('file.Staff Note') }}:</strong></u></div></div><div class="row"><div class="col-md-6">'+sale_note+'</div><div class="col-md-6">'+sale_staff+'</div></div>';
        //     $('#sale-content').html(htmltext);
        //     $('#sale-footer').html(htmlfooter);
        //     $('#sale-details').modal('show');
        // }

        $(document).on('submit', '.payment-form', function(e) {
            if ($('input[name="paying_amount"]').val() < parseFloat($('#amount').val())) {
                alert('Paying amount cannot be bigger than recieved amount');
                $('input[name="amount"]').val('');
                $(".change").text(parseFloat($('input[name="paying_amount"]').val() - $('#amount').val()).toFixed(
                    2));
                e.preventDefault();
            } else if ($('input[name="edit_paying_amount"]').val() < parseFloat($('input[name="edit_amount"]')
                .val())) {
                alert('Paying amount cannot be bigger than recieved amount');
                $('input[name="edit_amount"]').val('');
                $(".change").text(parseFloat($('input[name="edit_paying_amount"]').val() - $(
                    'input[name="edit_amount"]').val()).toFixed(2));
                e.preventDefault();
            }

            $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', false);
        });

        if (all_permission.indexOf("sales-delete") == -1)
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
