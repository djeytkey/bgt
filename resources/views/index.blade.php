@extends('layout.main')
@section('content')
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @php
        if ($general_setting->theme == 'default.css') {
            $color = '#733686';
            $color_rgba = 'rgba(115, 54, 134, 0.8)';
        } elseif ($general_setting->theme == 'green.css') {
            $color = '#2ecc71';
            $color_rgba = 'rgba(46, 204, 113, 0.8)';
        } elseif ($general_setting->theme == 'blue.css') {
            $color = '#3498db';
            $color_rgba = 'rgba(52, 152, 219, 0.8)';
        } elseif ($general_setting->theme == 'dark.css') {
            $color = '#34495e';
            $color_rgba = 'rgba(52, 73, 94, 0.8)';
        }
    @endphp
    <div class="row">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="brand-text float-left mt-4">
                    <h3>{{ ucfirst(trans('file.welcome')) }} <span>{{ strtoupper(Auth::user()->last_name) }}
                            {{ ucfirst(Auth::user()->first_name) }}</span> </h3>
                </div>
                @if (Auth::user()->role_id == 1)
                    <div class="filter-toggle btn-group">
                        <a href="?start_date={{ date('Y-m-d') }}&end_date={{ date('Y-m-d') }}&current_filter=daily"
                            class="btn btn-secondary date-btn @if ($current_filter == 'daily') active @endif"
                            data-start_date="{{ date('Y-m-d') }}" data-end_date="{{ date('Y-m-d') }}"
                            data-current_filter="daily">{{ trans('file.Today') }}</a>
                        <a href="?start_date={{ date('Y-m-d', strtotime(' -7 day')) }}&end_date={{ date('Y-m-d') }}&current_filter=weekly"
                            class="btn btn-secondary date-btn @if ($current_filter == 'weekly') active @endif"
                            data-start_date="{{ date('Y-m-d', strtotime(' -7 day')) }}"
                            data-end_date="{{ date('Y-m-d') }}"
                            data-current_filter="weekly">{{ trans('file.Last 7 Days') }}</a>
                        <a href="?start_date={{ date('Y') . '-' . date('m') . '-' . '01' }}&end_date={{ date('Y-m-d') }}&current_filter=monthly"
                            class="btn btn-secondary date-btn @if ($current_filter == 'monthly') active @endif"
                            data-start_date="{{ date('Y') . '-' . date('m') . '-' . '01' }}"
                            data-end_date="{{ date('Y-m-d') }}"
                            data-current_filter="monthly">{{ trans('file.This Month') }}</a>
                        <a href="?start_date={{ date('Y') . '-01' . '-01' }}&end_date={{ date('Y') . '-12' . '-31' }}&current_filter=yearly"
                            class="btn btn-secondary date-btn @if ($current_filter == 'yearly') active @endif"
                            data-start_date="{{ date('Y') . '-01' . '-01' }}"
                            data-end_date="{{ date('Y') . '-12' . '-31' }}"
                            data-current_filter="yearly">{{ trans('file.This Year') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Counts Section -->
    <section class="dashboard-counts">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 form-group">
                    <!--Ligne des commandes -->
                    <div class="row">
                        <!-- Count item widget-->
                        <div class="col-sm-2">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-graph-bar" style="color: #733686"></i></div>
                                <div class="name"><strong
                                        style="color: #733686">{{ trans('file.Total Orders') }}</strong></div>
                                <div class="count-number total-order-data"><strong
                                        style="color: #733686">{{ number_format((float) $total_sales, 0, '.', ' ') }}</strong>
                                </div>
                            </div>
                        </div>
                        <!-- Count item widget-->
                        <div class="col-sm-2">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-return" style="color: #00c689"></i></div>
                                <div class="name"><strong style="color: #00c689">{{ trans('file.Delivered') }}</strong>
                                </div>
                                <div class="count-number delivered-data"><strong
                                        style="color: #00c689">{{ number_format((float) $livre, 0, '.', ' ') }}</strong>
                                </div>
                            </div>
                        </div>
                        <!-- Count item widget-->
                        <div class="col-sm-2">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-media-loop" style="color: #ff8952"></i></div>
                                <div class="name"><strong style="color: #ff8952">{{ trans('file.In progress') }}</strong>
                                </div>
                                <div class="count-number in-progress-data"><strong
                                        style="color: #ff8952">{{ number_format((float) $en_cours, 0, '.', ' ') }}</strong>
                                </div>
                            </div>
                        </div>
                        <!-- Count item widget-->
                        <div class="col-sm-2">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-media-loop" style="color: red"></i></div>
                                <div class="name"><strong style="color: red">{{ trans('file.Returned') }}</strong></div>
                                <div class="count-number in-progress-data"><strong
                                        style="color: red">{{ number_format((float) $returned, 0, '.', ' ') }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-media-loop" style="color: red"></i></div>
                                <div class="name"><strong style="color: red">{{ trans('file.Refused') }}</strong></div>
                                <div class="count-number in-progress-data"><strong
                                        style="color: red">{{ number_format((float) $refused, 0, '.', ' ') }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-media-loop" style="color: red"></i></div>
                                <div class="name"><strong style="color: red">{{ trans('file.New Sales') }}</strong></div>
                                <div class="count-number in-progress-data"><strong
                                        style="color: red">{{ number_format((float) $new_sale, 0, '.', ' ') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Ligne des Retraits -->
                    <div class="row">
                        <!-- Count item widget-->
                        <div class="col-sm-{{ $profit_row }}">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                                <div class="name"><strong style="color: #297ff9">{{ trans('file.Profit') }}</strong>
                                </div>
                                <div class="count-number total-profit-data">
                                    {{ number_format((float) $profit, 2, '.', ' ') }}
                                </div>
                            </div>
                        </div>
                        <!-- Count item widget-->
                        <div class="col-sm-{{ $profit_row }}">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                                <div class="name"><strong style="color: #297ff9">{{ trans('file.paid') }}</strong>
                                </div>
                                <div class="count-number profit-claimed-data">
                                    {{ number_format((float) $total_withdraws, 2, '.', ' ') }}
                                    <span
                                        style="display: inline-flex; font-size: 0.65em;">({{ $demande_withdraws }})</span>
                                </div>
                            </div>
                        </div>
                        <!-- Count item widget-->
                        <div class="col-sm-{{ $profit_row }}">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                                <div class="name"><strong style="color: #297ff9">{{ trans('file.Remaining') }}</strong>
                                </div>
                                <div class="count-number profit-remain-data">
                                    {{ number_format((float) $reste, 2, '.', ' ') }}
                                </div>
                            </div>
                        </div>
                        @if (Auth::user()->role_id == 1)
                            <!-- Count item widget-->
                            <div class="col-sm-{{ $profit_row }}">
                                <div class="wrapper count-title text-center">
                                    <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                                    <div class="name"><strong
                                            style="color: #297ff9">{{ trans('file.Remaining') }}</strong></div>
                                    <div class="count-number profit-remain-data">
                                        {{ number_format((float) $reste, 2, '.', ' ') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!--Ligne des parrains -->
                    @if ($is_referral)
                        <div class="row">
                            <!-- Count item widget-->
                            <div class="col-sm-{{ $profit_row }}">
                                <div class="wrapper count-title text-center">
                                    <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                                    <div class="name"><strong
                                            style="color: #297ff9">{{ trans('file.Profit') }}</strong>
                                    </div>
                                    <div class="count-number total-profit-data">
                                        {{ number_format((float) $total_referral, 2, '.', ' ') }}
                                    </div>
                                </div>
                            </div>
                            <!-- Count item widget-->
                            <div class="col-sm-{{ $profit_row }}">
                                <div class="wrapper count-title text-center">
                                    <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                                    <div class="name"><strong style="color: #297ff9">{{ trans('file.paid') }}</strong>
                                    </div>
                                    <div class="count-number profit-claimed-data">
                                        {{ number_format((float) $total_paid_referral, 2, '.', ' ') }}
                                    </div>
                                </div>
                            </div>
                            <!-- Count item widget-->
                            <div class="col-sm-{{ $profit_row }}">
                                <div class="wrapper count-title text-center">
                                    <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                                    <div class="name"><strong
                                            style="color: #297ff9">{{ trans('file.Remaining') }}</strong>
                                    </div>
                                    <div class="count-number profit-remain-data">
                                        {{ number_format((float) $total_m_referral, 2, '.', ' ') }}
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->role_id == 1)
                                <!-- Count item widget-->
                                <div class="col-sm-{{ $profit_row }}">
                                    <div class="wrapper count-title text-center">
                                        <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                                        <div class="name"><strong
                                                style="color: #297ff9">{{ trans('file.Remaining') }}</strong></div>
                                        <div class="count-number profit-remain-data">
                                            {{ number_format((float) $total_m_referral, 2, '.', ' ') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    @if (Auth::user()->role_id == 1)
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4>{{ trans('file.Best Seller') }}</h4>
                                        <div class="right-column">
                                            <div class="badge badge-primary">{{ trans('file.top') }} 10</div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>SL No</th>
                                                    <th>{{ trans('file.Product Details') }}</th>
                                                    <th>{{ trans('file.qty') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($best_selling_qty as $key => $sale)
                                                    <?php $product = DB::table('products')->find($sale->product_id); ?>
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $product->name }}&nbsp;&nbsp;[{{ $product->code }}]</td>
                                                        <td>{{ $sale->sold_qty }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4>{{ trans('file.Best Saler') }}</h4>
                                        <div class="right-column">
                                            <div class="badge badge-primary">{{ trans('file.top') }} 10</div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>SL No</th>
                                                    <th>{{ trans('file.Vendeur') }}</th>
                                                    <th>{{ trans('file.Montant') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($best_saler as $key => $saler)
                                                    <?php $vendeur = DB::table('users')->find($saler->user_id); ?>
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ strtoupper($vendeur->last_name) }}&nbsp;{{ ucfirst($vendeur->first_name) }}
                                                        </td>
                                                        <td>{{ number_format((float) $saler->t_gain, 0, '.', ' ') }} DH</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mt-4">
                                <div class="card line-chart-example">
                                    <div class="card-header d-flex align-items-center">
                                        <h4>{{ trans('file.Sales Flow') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <!-- <pre>{{ print_r($sales_by_month, true) }}</pre>
                                            <pre>{{ print_r($month, true) }}</pre> -->

                                        <canvas id="salesflow" data-color="{{ $color }}"
                                            data-color_rgba="{{ $color_rgba }}"
                                            data-salesbymonth="{{ json_encode($sales_by_month) }}"
                                            data-month="{{ json_encode($month) }}"
                                            data-label1="{{ trans('file.Ventes par mois') }}"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $("ul#dashboard").siblings('a').attr('aria-expanded', 'true');
        $("ul#dashboard").addClass("show");
        $("ul#dashboard #dashboard-1").addClass("active");

        // Show and hide color-switcher
        $(".color-switcher .switcher-button").on('click', function() {
            $(".color-switcher").toggleClass("show-color-switcher", "hide-color-switcher", 300);
        });

        // Color Skins
        $('a.color').on('click', function() {
            /*var title = $(this).attr('title');
            $('#style-colors').attr('href', 'css/skin-' + title + '.css');
            return false;*/
            $.get('setting/general_setting/change-theme/' + $(this).data('color'), function(data) {});
            var style_link = $('#custom-style').attr('href').replace(/([^-]*)$/, $(this).data('color'));
            $('#custom-style').attr('href', style_link);
        });

        // $(".date-btn").on("click", function() {
        //     $(".date-btn").removeClass("active");
        //     $(this).addClass("active");
        //     var start_date = $(this).data('start_date');
        //     var end_date = $(this).data('end_date');
        //     var current_filter = $(this).data('current_filter');
        //     alert("Start : " + start_date + "\nEnd : " + end_date);
        //     $.get('dashboard-filter/?start_date=' + start_date + '&end_date=' + end_date + '&current_filter=' +
        //         current_filter);
        // });

        function dashboardFilter(data) {
            $('.revenue-data').hide();
            $('.revenue-data').html(parseFloat(data[0]).toFixed(2));
            $('.revenue-data').show(500);

            $('.return-data').hide();
            $('.return-data').html(parseFloat(data[1]).toFixed(2));
            $('.return-data').show(500);

            $('.profit-data').hide();
            $('.profit-data').html(parseFloat(data[2]).toFixed(2));
            $('.profit-data').show(500);

            $('.purchase_return-data').hide();
            $('.purchase_return-data').html(parseFloat(data[3]).toFixed(2));
            $('.purchase_return-data').show(500);
        }
    </script>
@endsection
