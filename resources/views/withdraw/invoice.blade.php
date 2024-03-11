<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{ url('public/logo', $general_setting->site_logo) }}" />
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/font-awesome/css/font-awesome.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/css/adminlte.min.css'); ?>" type="text/css">
    <title>{{ $general_setting->site_title }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
</head>
<style>
    td {
        vertical-align: middle !important;
    }

    ul {
        margin-bottom: 0;
        padding-left: 18px;
    }
</style>

<body>
    <div class="content-wrapper" style="margin-left: 0 !important;">
        <section class="content container">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="invoice p-3 mb-3">
                            <div class="row mb-5">
                                <div class="col-sm-7 invoice-col mb-5">
                                    @if ($general_setting->site_logo)
                                        <img src="{{ url('public/logo', $general_setting->site_logo) }}" width="150"
                                            style="margin:10px 0;">
                                    @endif
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-5 invoice-col">
                                    <?php
                                    $fact_id = request()->route('id');
                                    $lims_fact_data = DB::table('withdrawals')
                                        ->where('id', $fact_id)
                                        ->first();
                                    $date = new DateTime($lims_fact_data->created_at);
                                    $date_fact = $date->format('d-m-Y');
                                    $heure_fact = $date->format('H:m:i');
                                    $lims_sale_data = DB::table('sales')
                                        ->where('withdrawal_id', $fact_id)
                                        ->first();
                                    $user = \App\User::find($lims_sale_data->user_id);
                                    ?>
                                    <h5><b>Facture N° : {{ $lims_fact_data->facture_no }}</b></h5><br>
                                    <table style="width: 100%">
                                        <tr>
                                            <td><b>{{ strtoupper($user->last_name) }}&nbsp;{{ strtoupper($user->first_name) }}</b></td>
                                            <td rowspan="3">
                                                @if ($lims_fact_data->status == 1)
                                                <img src="{{ asset('public/images/paid.png') }}" alt="paid" width="100" />
                                                @else
                                                <img src="{{ asset('public/images/virement.png') }}" alt="virement" width="100" />
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Date : </b>{{ $date_fact }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Heure : </b>{{ $heure_fact }}</td>
                                        </tr>
                                    </table> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Référence N°</th>
                                                <th>Products</th>
                                                <th>Grand Total</th>
                                                <th>Remise</th>
                                                <th>Prix Original</th>
                                                <th>Livraison</th>
                                                <th>Bénifice</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $fact_id = request()->route('id');
                                            $lims_sale_data = DB::table('sales')->where('withdrawal_id', $fact_id)->distinct()->get();
                                            $total_cde = count($lims_sale_data);
                                            $original_price_total = 0;
                                            $grand_total_total = 0;
                                            $total_discount_total = 0;
                                            $livraison_total = 0;
                                            $gain_total = 0;
                                            foreach ($lims_sale_data as $key=>$sale) 
                                            {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $key + 1; ?>
                                                </td>
                                                <td>
                                                    <?php echo $sale->reference_no; ?>
                                                </td>
                                                <?php
                                                $lims_product_sale_data = DB::table('product_sales')
                                                    ->where('sale_id', $sale->id)
                                                    ->get();
                                                $product_qty = '';
                                                $variant_name = '';
                                                $product_name = '';
                                                $original_price = 0;
                                                
                                                if (!empty($lims_product_sale_data)) {
                                                    $list_products = '<ul class="table-products">';
                                                    foreach ($lims_product_sale_data as $key => $product_sale_data) {
                                                        $original_price += $product_sale_data->original_price * $product_sale_data->qty;
                                                        $lims_product_data = DB::table('products')->find($product_sale_data->product_id);
                                                        $product_name = $lims_product_data->name;
                                                        $product_qty = $product_sale_data->qty;
                                                        if ($product_sale_data->variant_id) {
                                                            $variant_data = DB::table('variants')
                                                                ->select('name')
                                                                ->find($product_sale_data->variant_id);
                                                            $variant_name = $variant_data->name;
                                                        } else {
                                                            $variant_name = '';
                                                        }
                                                
                                                        $list_products .= '<li class="single-table-product">' . $product_name . '&nbsp;(&nbsp;' . str_pad($product_qty, 2, '0', STR_PAD_LEFT) . '&nbsp;/&nbsp;' . $variant_name . '&nbsp;)</li>';
                                                    }
                                                    $list_products .= '</ul>';
                                                } else {
                                                    $list_products = '--';
                                                }
                                                
                                                $gain = $sale->grand_total + $sale->total_discount - $original_price - $sale->livraison;
                                                
                                                $original_price_total += $original_price;
                                                $grand_total_total += $sale->grand_total;
                                                $total_discount_total += $sale->total_discount;
                                                $livraison_total += $sale->livraison;
                                                $gain_total += $gain;
                                                
                                                ?>
                                                <td>
                                                    <?php echo $list_products; ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($sale->grand_total, 2, '.', ' '); ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($sale->total_discount, 2, '.', ' '); ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($original_price, 2, '.', ' '); ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($sale->livraison, 2, '.', ' '); ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($gain, 2, '.', ' '); ?>
                                                </td>
                                            </tr>
                                            <?php
                                            }                        
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">

                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6">

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th>Nombre Commandes</th>
                                                <td>{{ $total_cde }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Commandes</th>
                                                <td>{{ number_format((float)$grand_total_total, 2, '.', ' ') }} DHS</td>
                                            </tr>
                                            <tr>
                                                <th>Total Remises</th>
                                                <td>{{ number_format((float)$total_discount_total, 2, '.', ' ') }} DHS</td>
                                            </tr>
                                            <tr>
                                                <th>Total Prix Original</th>
                                                <td>{{ number_format((float)$original_price_total, 2, '.', ' ') }} DHS</td>
                                            </tr>
                                            <tr>
                                                <th>Total Livraison</th>
                                                <td>{{ number_format((float)$livraison_total, 2, '.', ' ') }} DHS</td>
                                            </tr>
                                            <tr>
                                                <th>Total Bénifice</th>
                                                <td style="color: red;"><b>{{ number_format((float)$gain_total, 2, '.', ' ') }} DHS</b></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row no-print">
                                <div class="col-sm-12">
                                    <button type="button" onclick="auto_print();" class="btn btn-primary float-right" style="margin-right: 5px;">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
    </div>

    <script type="text/javascript">
        localStorage.clear();

        window.addEventListener('load', function() {
            window.print();
        });

        function auto_print() {     
            window.print()
        }
        // setTimeout(auto_print, 1000);
    </script>
    <!-- jQuery -->
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery/jquery.min.js'); ?>"></script>
    <!-- Bootstrap 4 -->
    <script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script type="text/javascript" src="<?php echo asset('public/js/adminlte.min.js'); ?>"></script>
</body>

</html>
