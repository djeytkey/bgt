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
                                    $lims_fact_data = DB::table('withdraw_referrals')
                                        ->where('facture_no', $fact_id)
                                        ->first();

                                    $date = new DateTime($lims_fact_data->created_at);
                                    $date_fact = $date->format('d-m-Y');
                                    $heure_fact = $date->format('H:m:i');

                                    $parrain = \App\User::find($lims_fact_data->referral_id);
                                    $p_nom = strtoupper($parrain->last_name) . ' ' . strtoupper($parrain->first_name);
                                    ?>
                                    <h5><b>Facture N° : {{ $lims_fact_data->facture_no }}</b></h5><br>
                                    <table style="width: 100%">
                                        <tr>
                                            <td><b>{{ $p_nom }}</b></td>
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
                                                <th>Vendeur</th>
                                                <th>Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $fact_id = request()->route('id');
                                            $lims_fact_data = DB::table('withdraw_referrals')
                                                                ->where('facture_no', $fact_id)
                                                                ->get();
                                            $total_vdr = count($lims_fact_data);
                                            $gain_total = 0;
                                            foreach ($lims_fact_data as $key=>$facture) 
                                            {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $key + 1; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $lims_user_data = \App\User::find($facture->referral_user_id);
                                                    echo strtoupper($lims_user_data->last_name) . ' ' . strtoupper($lims_user_data->first_name); 
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        echo number_format($facture->withdraw_amount, 2, '.', ' ');
                                                        $gain_total += $facture->withdraw_amount;
                                                    ?>
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
                                                <th>Nombre Vendeurs</th>
                                                <td>{{ $total_vdr }}</td>
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
