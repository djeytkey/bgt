<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{ url('public/logo/favicon-bgt.png', '') }}" />
    <title>{{ $general_setting->site_title }}</title>
    <meta name="description" content="">
    <!--<meta name="viewport" content="width=1024">-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ url('manifest.json') }}">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap-datepicker.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap-colorpicker.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/jquery-timepicker/jquery.timepicker.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/awesome-bootstrap-checkbox.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap-select.min.css'); ?>" type="text/css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/font-awesome/css/font-awesome.min.css'); ?>" type="text/css">
    <!-- Drip icon font-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/dripicons/webfont.css'); ?>" type="text/css">
    <!-- Google fonts - Roboto -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:400,500,700"> --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="<?php echo asset('public/css/grasp_mobile_progress_circle-1.0.0.min.css'); ?>" type="text/css">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css'); ?>" type="text/css">
    <!-- virtual keybord stylesheet-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/keyboard/css/keyboard.css'); ?>" type="text/css">
    <!-- date range stylesheet-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/daterange/css/daterangepicker.min.css'); ?>" type="text/css">
    <!-- table sorter stylesheet-->
    <link rel="stylesheet" type="text/css" href="<?php echo asset('public/vendor/datatable/dataTables.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo asset('public/css/style.default.css'); ?>" id="theme-stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/css/dropzone.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('public/css/style.css'); ?>">
    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery/jquery-ui.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery/bootstrap-datepicker.min.js'); ?>"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/2.14.1/moment.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery/jquery.timepicker.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/popper.js/umd/popper.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap/js/bootstrap-colorpicker.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap/js/bootstrap-select.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/keyboard/js/jquery.keyboard.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/keyboard/js/jquery.keyboard.extension-autocomplete.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/js/grasp_mobile_progress_circle-1.0.0.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery.cookie/jquery.cookie.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/chart.js/Chart.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery-validation/jquery.validate.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/js/charts-custom.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/js/front.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/daterange/js/moment.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/daterange/js/knockout-3.4.2.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/daterange/js/daterangepicker.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/tinymce/js/tinymce/tinymce.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/js/dropzone.js'); ?>"></script>

    <!-- table sorter js-->
    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/pdfmake.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/vfs_fonts.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/jquery.dataTables.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/dataTables.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/dataTables.buttons.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.colVis.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.html5.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.print.min.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/sum().js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/datatable/dataTables.checkboxes.min.js'); ?>"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js">
    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js">
    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js">
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="<?php echo asset('public/js/loadingoverlay.min.js'); ?>"></script>
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="<?php echo asset('public/css/custom-' . $general_setting->theme); ?>" type="text/css" id="custom-style">
</head>

<body onload="myFunction()">
    <div id="loader"></div>
    <!-- Side Navbar -->
    <nav class="side-navbar shrink">
        <div class="side-navbar-wrapper">
            <!-- Sidebar Header    -->
            <!-- Sidebar Navigation Menus-->
            <div class="main-menu">
                <ul id="side-main-menu" class="side-menu list-unstyled">
                    {{-- Tableau de bord Menu --}}
                    <?php
                    $role = DB::table('roles')->find(Auth::user()->role_id);
                    ?>
                    @if ($role->id == "1")
                    <li><a href="#dashboard" aria-expanded="false" data-toggle="collapse"> <i
                                class="dripicons-meter"></i><span>{{ __('file.dashboard') }}</span><span></a>
                        <ul id="dashboard" class="collapse list-unstyled ">
                            <li id="dashboard-1"><a href="{{ url('/') }}">{{ __('file.Dashboard 1') }}</a>
                            </li>
                            <li id="dashboard-2"><a href="{{ url('/gain') }}">{{ __('file.Dashboard 2') }}</a>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li><a href="{{ url('/') }}"> <i
                                class="dripicons-meter"></i><span>{{ __('file.dashboard') }}</span></a></li>
                    @endif
                    <?php
                    $role = DB::table('roles')->find(Auth::user()->role_id);
                    $index_permission = DB::table('permissions')
                        ->where('name', 'products-index')
                        ->first();
                    $index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $index_permission->id], ['role_id', $role->id]])
                        ->first();
                    $add_permission = DB::table('permissions')
                        ->where('name', 'products-add')
                        ->first();
                    $add_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $add_permission->id], ['role_id', $role->id]])
                        ->first();
                    $category_permission = DB::table('permissions')
                        ->where('name', 'categories-index')
                        ->first();
                    $category_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $category_permission->id], ['role_id', $role->id]])
                        ->first();
                    $print_barcode = DB::table('permissions')
                        ->where('name', 'print_barcode')
                        ->first();
                    $print_barcode_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $print_barcode->id], ['role_id', $role->id]])
                        ->first();
                    
                    $stock_count = DB::table('permissions')
                        ->where('name', 'stock_count')
                        ->first();
                    $stock_count_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $stock_count->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    {{-- Produit Menu --}}
                    @if (
                        $category_permission_active ||
                            $index_permission_active ||
                            $add_permission_active ||
                            $print_barcode_active ||
                            $stock_count_active)
                        <li><a href="#product" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-list"></i><span>{{ __('file.product') }}</span><span></a>
                            <ul id="product" class="collapse list-unstyled ">
                                @if ($index_permission_active)
                                    <li id="product-list-menu"><a
                                            href="{{ route('products.index') }}">{{ __('file.product_list') }}</a>
                                    </li>
                                @endif
                                @if ($add_permission_active)
                                    <li id="product-create-menu"><a
                                            href="{{ route('products.create') }}">{{ __('file.add_product') }}</a>
                                    </li>
                                @endif
                                @if ($print_barcode_active)
                                    <li id="printBarcode-menu"><a
                                            href="{{ route('product.printBarcode') }}">{{ __('file.print_barcode') }}</a>
                                    </li>
                                @endif
                                @if ($category_permission_active)
                                    <li id="category-menu"><a
                                            href="{{ route('category.index') }}">{{ __('file.category') }}</a></li>
                                @endif
                                @if ($stock_count_active)
                                    <li id="stock-count-menu"><a
                                            href="{{ route('stock-count.index') }}">{{ trans('file.Stock Count') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Achat Menu --}}
                    <?php
                    $index_permission = DB::table('permissions')
                        ->where('name', 'purchases-index')
                        ->first();
                    $index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $index_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $add_permission = DB::table('permissions')
                        ->where('name', 'purchases-add')
                        ->first();
                    $add_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $add_permission->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    @if ($index_permission_active || $add_permission_active)
                        <li><a href="#purchase" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-card"></i><span>{{ trans('file.Purchase') }}</span></a>
                            <ul id="purchase" class="collapse list-unstyled ">
                                @if ($index_permission_active)
                                    <li id="purchase-list-menu"><a
                                            href="{{ route('purchases.index') }}">{{ trans('file.Purchase List') }}</a>
                                    </li>
                                @endif
                                @if ($add_permission_active)
                                    <li id="purchase-create-menu"><a
                                            href="{{ route('purchases.create') }}">{{ trans('file.Add Purchase') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Vente Menu --}}
                    <?php
                    $sale_index_permission = DB::table('permissions')
                        ->where('name', 'sales-index')
                        ->first();
                    $sale_index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $sale_index_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $sale_add_permission = DB::table('permissions')
                        ->where('name', 'sales-add')
                        ->first();
                    $sale_add_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $sale_add_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $add_discount_permission = DB::table('permissions')
                        ->where('name', 'sales-add-discount')
                        ->first();
                    $add_discount_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $add_discount_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $export_index_permission = DB::table('permissions')
                        ->where('name', 'sales-export')
                        ->first();
                    $export_index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $export_index_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $delivery_index_permission = DB::table('permissions')
                        ->where('name', 'delivery-index')
                        ->first();
                    $delivery_index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $delivery_index_permission->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    @if (
                        $sale_index_permission_active ||
                            $sale_add_permission_active ||
                            $add_discount_permission_active ||
                            $export_index_permission_active ||
                            $delivery_index_permission_active)
                        <li><a href="#sale" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-cart"></i><span>{{ trans('file.Sale') }}</span></a>
                            <ul id="sale" class="collapse list-unstyled ">
                                @if ($sale_index_permission_active)
                                    <li id="sale-list-menu"><a
                                            href="{{ route('sales.index') }}">{{ trans('file.Sale List') }}</a>
                                    </li>
                                @endif
                                @if ($sale_add_permission_active)
                                    <li id="sale-create-menu"><a
                                            href="{{ route('sales.create') }}">{{ trans('file.Add Sale') }}</a>
                                    </li>
                                @endif
                                @if ($add_discount_permission_active)
                                    <li><a id="add-discount" href="" data-toggle="modal"
                                            data-target="#discount-modal"> {{ trans('file.Add Discount') }}</a>
                                    </li>
                                @endif
                                @if ($export_index_permission_active)
                                    <li id="export-menu"><a
                                            href="{{ route('sales.export') }}">{{ trans('file.Export List') }}</a>
                                    </li>
                                @endif
                                @if ($delivery_index_permission_active)
                                    <li id="delivery-menu"><a
                                            href="{{ route('delivery.index') }}">{{ trans('file.Delivery List') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Dépense Menu --}}
                    <?php
                    $index_permission = DB::table('permissions')
                        ->where('name', 'expenses-index')
                        ->first();
                    $index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $index_permission->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    @if ($index_permission_active)
                        <li><a href="#expense" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-wallet"></i><span>{{ trans('file.Expense') }}</span></a>
                            <ul id="expense" class="collapse list-unstyled ">
                                <li id="exp-cat-menu"><a
                                        href="{{ route('expense_categories.index') }}">{{ trans('file.Expense Category') }}</a>
                                </li>
                                <li id="exp-list-menu"><a
                                        href="{{ route('expenses.index') }}">{{ trans('file.Expense List') }}</a>
                                </li>
                                <?php
                                $add_permission = DB::table('permissions')
                                    ->where('name', 'expenses-add')
                                    ->first();
                                $add_permission_active = DB::table('role_has_permissions')
                                    ->where([['permission_id', $add_permission->id], ['role_id', $role->id]])
                                    ->first();
                                ?>
                                @if ($add_permission_active)
                                    <li><a id="add-expense" href=""> {{ trans('file.Add Expense') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Retrait Menu --}}
                    <?php
                    $withdraw_index_permission = DB::table('permissions')
                        ->where('name', 'withdraw-index')
                        ->first();
                    $withdraw_index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $withdraw_index_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $withdraw_invoicing_permission = DB::table('permissions')
                        ->where('name', 'withdraw-facturation')
                        ->first();
                    $withdraw_invoicing_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $withdraw_invoicing_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $withdraw_requests_permission = DB::table('permissions')
                        ->where('name', 'withdraw-demandes')
                        ->first();
                    $withdraw_requests_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $withdraw_requests_permission->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    @if ($withdraw_index_permission_active || $withdraw_invoicing_permission_active || $withdraw_requests_permission_active)
                        <li><a href="#withdraw" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-card"></i><span>{{ trans('file.Withdraw') }}</span></a>
                            <ul id="withdraw" class="collapse list-unstyled ">
                                @if ($withdraw_index_permission_active)
                                    <li id="withdraw-list-menu">
                                        <a href="{{ route('withdraw.index') }}">{{ trans('file.Withdraw List') }}</a>
                                    </li>
                                @endif
                                <?php
                                $add_permission = DB::table('permissions')
                                    ->where('name', 'withdraw-add')
                                    ->first();
                                $add_permission_active = DB::table('role_has_permissions')
                                    ->where([['permission_id', $add_permission->id], ['role_id', $role->id]])
                                    ->first();
                                ?>
                                @if ($withdraw_invoicing_permission_active)
                                    <li id="withdraw-facturation-menu">
                                        <a
                                            href="{{ route('withdraw.facturation') }}">{{ trans('file.Facturation') }}</a>
                                    </li>
                                @endif
                                @if ($add_permission_active)
                                    <li>
                                        <a id="demande-retrait" href="" data-toggle="modal"
                                            data-target="#withdraw-modal"> {{ trans('file.Demander Un Retrait') }}</a>
                                    </li>
                                @endif
                                @if ($withdraw_requests_permission_active)
                                    <li id="withdraw-demandes-menu">
                                        <a
                                            href="{{ route('withdraw.demandes-retrait') }}">{{ trans('file.Demandes de retrait') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Referral Menu --}}
                    <?php
                    $referral_index_permission = DB::table('permissions')
                        ->where('name', 'referral-index')
                        ->first();
                    $referral_index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $referral_index_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $referral_invoicing_permission = DB::table('permissions')
                        ->where('name', 'referral-facturation')
                        ->first();
                    $referral_invoicing_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $referral_invoicing_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $add_referral_permission = DB::table('permissions')
                        ->where('name', 'referral-add')
                        ->first();
                    $add_referral_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $add_referral_permission->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    @if ($referral_index_permission_active || $referral_invoicing_permission_active || $add_referral_permission_active)
                        <li><a href="#referral" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-card"></i><span>{{ trans('file.Referral') }}</span></a>
                            <ul id="referral" class="collapse list-unstyled ">
                                @if ($referral_index_permission_active)
                                    <li id="referral-list-menu">
                                        <a href="{{ route('referral.index') }}">{{ trans('file.Referral List') }}</a>
                                    </li>
                                @endif
                                @if ($referral_invoicing_permission_active)
                                    <li id="referral-facturation-menu">
                                        <a
                                            href="{{ route('referral.facturation') }}">{{ trans('file.Facturation') }}</a>
                                    </li>
                                @endif
                                @if ($add_referral_permission_active)
                                    <li>
                                        <a id="add-referral" href="" data-toggle="modal"
                                            data-target="#referral-modal"> {{ trans('file.Add Referral') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Devis Menu --}}
                    <?php
                    $index_permission = DB::table('permissions')
                        ->where('name', 'quotes-index')
                        ->first();
                    $index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $index_permission->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    @if ($index_permission_active)
                        <li><a href="#quotation" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-document"></i><span>{{ trans('file.Quotation') }}</span><span></a>
                            <ul id="quotation" class="collapse list-unstyled ">
                                <li id="quotation-list-menu"><a
                                        href="{{ route('quotations.index') }}">{{ trans('file.Quotation List') }}</a>
                                </li>
                                <?php
                                $add_permission = DB::table('permissions')
                                    ->where('name', 'quotes-add')
                                    ->first();
                                $add_permission_active = DB::table('role_has_permissions')
                                    ->where([['permission_id', $add_permission->id], ['role_id', $role->id]])
                                    ->first();
                                ?>
                                @if ($add_permission_active)
                                    <li id="quotation-create-menu"><a
                                            href="{{ route('quotations.create') }}">{{ trans('file.Add Quotation') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Transfert Menu --}}
                    <?php
                    $index_permission = DB::table('permissions')
                        ->where('name', 'transfers-index')
                        ->first();
                    $index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $index_permission->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    @if ($index_permission_active)
                        <li><a href="#transfer" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-export"></i><span>{{ trans('file.Transfer') }}</span></a>
                            <ul id="transfer" class="collapse list-unstyled ">
                                <li id="transfer-list-menu"><a
                                        href="{{ route('transfers.index') }}">{{ trans('file.Transfer List') }}</a>
                                </li>
                                <?php
                                $add_permission = DB::table('permissions')
                                    ->where('name', 'transfers-add')
                                    ->first();
                                $add_permission_active = DB::table('role_has_permissions')
                                    ->where([['permission_id', $add_permission->id], ['role_id', $role->id]])
                                    ->first();
                                ?>
                                @if ($add_permission_active)
                                    <li id="transfer-create-menu"><a
                                            href="{{ route('transfers.create') }}">{{ trans('file.Add Transfer') }}</a>
                                    </li>
                                    <li id="transfer-import-menu"><a
                                            href="{{ url('transfers/transfer_by_csv') }}">{{ trans('file.Import Transfer By CSV') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Retour Menu --}}
                    <?php
                    $sale_return_index_permission = DB::table('permissions')
                        ->where('name', 'returns-index')
                        ->first();
                    
                    $sale_return_index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $sale_return_index_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $purchase_return_index_permission = DB::table('permissions')
                        ->where('name', 'purchase-return-index')
                        ->first();
                    
                    $purchase_return_index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $purchase_return_index_permission->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    @if ($sale_return_index_permission_active || $purchase_return_index_permission_active)
                        <li><a href="#return" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-return"></i><span>{{ trans('file.return') }}</span></a>
                            <ul id="return" class="collapse list-unstyled ">
                                @if ($sale_return_index_permission_active)
                                    <li id="sale-return-menu"><a
                                            href="{{ route('return-sale.index') }}">{{ trans('file.Sale') }}</a></li>
                                @endif
                                @if ($purchase_return_index_permission_active)
                                    <li id="purchase-return-menu"><a
                                            href="{{ route('return-purchase.index') }}">{{ trans('file.Purchase') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Comptabilité Menu --}}
                    <?php
                    $index_permission = DB::table('permissions')
                        ->where('name', 'account-index')
                        ->first();
                    $index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $index_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $money_transfer_permission = DB::table('permissions')
                        ->where('name', 'money-transfer')
                        ->first();
                    $money_transfer_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $money_transfer_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $balance_sheet_permission = DB::table('permissions')
                        ->where('name', 'balance-sheet')
                        ->first();
                    $balance_sheet_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $balance_sheet_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $account_statement_permission = DB::table('permissions')
                        ->where('name', 'account-statement')
                        ->first();
                    $account_statement_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $account_statement_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    ?>
                    @if (
                        $index_permission_active ||
                            $balance_sheet_permission_active ||
                            $account_statement_permission_active ||
                            $money_transfer_permission_active)
                        <li class=""><a href="#account" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-briefcase"></i><span>{{ trans('file.Accounting') }}</span></a>
                            <ul id="account" class="collapse list-unstyled ">
                                @if ($index_permission_active)
                                    <li id="account-list-menu"><a
                                            href="{{ route('accounts.index') }}">{{ trans('file.Account List') }}</a>
                                    </li>
                                    <li><a id="add-account" href="">{{ trans('file.Add Account') }}</a></li>
                                @endif
                                {{-- @if ($money_transfer_permission_active)
                                    <li id="money-transfer-menu"><a
                                            href="{{ route('money-transfers.index') }}">{{ trans('file.Money Transfer') }}</a>
                                    </li>
                                @endif
                                @if ($balance_sheet_permission_active)
                                    <li id="balance-sheet-menu"><a
                                            href="{{ route('accounts.balancesheet') }}">{{ trans('file.Balance Sheet') }}</a>
                                    </li>
                                @endif --}}
                                @if ($account_statement_permission_active)
                                    <li id="account-statement-menu"><a id="account-statement"
                                            href="">{{ trans('file.Account Statement') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Tickets Menu --}}
                    <?php
                    $index_permission = DB::table('permissions')
                        ->where('name', 'tickets-index')
                        ->first();
                    $index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $index_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    ?>
                    {{-- @if ($index_permission_active) --}}
                    <li class=""><a href="#ticket" aria-expanded="false" data-toggle="collapse"> <i
                                class="dripicons-briefcase"></i><span>{{ trans('file.Tickets') }}</span></a>
                        <ul id="ticket" class="collapse list-unstyled ">
                            {{-- @if ($index_permission_active) --}}
                            <li id="ticket-list-menu"><a
                                    href="{{ route('tickets.index') }}">{{ trans('file.Ticket List') }}</a>
                            </li>
                            <li><a id="add-ticket" href="">{{ trans('file.Add Ticket') }}</a></li>
                            {{-- @endif --}}
                        </ul>
                    </li>
                    {{-- @endif --}}
                    {{-- RH Menu --}}
                    <?php
                    $department = DB::table('permissions')
                        ->where('name', 'department')
                        ->first();
                    $department_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $department->id], ['role_id', $role->id]])
                        ->first();
                    $index_employee = DB::table('permissions')
                        ->where('name', 'employees-index')
                        ->first();
                    $index_employee_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $index_employee->id], ['role_id', $role->id]])
                        ->first();
                    $attendance = DB::table('permissions')
                        ->where('name', 'attendance')
                        ->first();
                    $attendance_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $attendance->id], ['role_id', $role->id]])
                        ->first();
                    $payroll = DB::table('permissions')
                        ->where('name', 'payroll')
                        ->first();
                    $payroll_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $payroll->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    {{-- @if (Auth::user()->role_id != 5)
                        <li class=""><a href="#hrm" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-user-group"></i><span>HRM</span></a>
                            <ul id="hrm" class="collapse list-unstyled ">
                                @if ($department_active)
                                    <li id="dept-menu"><a
                                            href="{{ route('departments.index') }}">{{ trans('file.Department') }}</a>
                                    </li>
                                @endif
                                @if ($index_employee_active)
                                    <li id="employee-menu"><a
                                            href="{{ route('employees.index') }}">{{ trans('file.Employee') }}</a>
                                    </li>
                                @endif
                                @if ($attendance_active)
                                    <li id="attendance-menu"><a
                                            href="{{ route('attendance.index') }}">{{ trans('file.Attendance') }}</a>
                                    </li>
                                @endif
                                @if ($payroll_active)
                                    <li id="payroll-menu"><a
                                            href="{{ route('payroll.index') }}">{{ trans('file.Payroll') }}</a></li>
                                @endif
                                <li id="holiday-menu"><a
                                        href="{{ route('holidays.index') }}">{{ trans('file.Holiday') }}</a></li>
                            </ul>
                        </li>
                    @endif --}}
                    {{-- Utilisateur Menu --}}
                    <?php
                    $user_index_permission_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'users-index'], ['role_id', $role->id]])
                        ->first();
                    
                    $customer_index_permission = DB::table('permissions')
                        ->where('name', 'customers-index')
                        ->first();
                    
                    $customer_index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $customer_index_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $biller_index_permission = DB::table('permissions')
                        ->where('name', 'billers-index')
                        ->first();
                    
                    $biller_index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $biller_index_permission->id], ['role_id', $role->id]])
                        ->first();
                    
                    $supplier_index_permission = DB::table('permissions')
                        ->where('name', 'suppliers-index')
                        ->first();
                    
                    $supplier_index_permission_active = DB::table('role_has_permissions')
                        ->where([['permission_id', $supplier_index_permission->id], ['role_id', $role->id]])
                        ->first();
                    ?>
                    @if (
                        $user_index_permission_active ||
                            $customer_index_permission_active ||
                            $biller_index_permission_active ||
                            $supplier_index_permission_active)
                        <li><a href="#people" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-user"></i><span>{{ trans('file.People') }}</span></a>
                            <ul id="people" class="collapse list-unstyled ">

                                @if ($user_index_permission_active)
                                    <li id="user-list-menu"><a
                                            href="{{ route('user.index') }}">{{ trans('file.User List') }}</a></li>
                                    <?php $user_add_permission_active = DB::table('permissions')
                                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                                        ->where([['permissions.name', 'users-add'], ['role_id', $role->id]])
                                        ->first();
                                    ?>
                                    @if ($user_add_permission_active)
                                        <li id="user-create-menu"><a
                                                href="{{ route('user.create') }}">{{ trans('file.Add User') }}</a>
                                        </li>
                                    @endif
                                @endif

                                {{-- @if ($customer_index_permission_active)
                                    <li id="customer-list-menu"><a
                                            href="{{ route('customer.index') }}">{{ trans('file.Customer List') }}</a>
                                    </li>
                                    <?php
                                    // $customer_add_permission = DB::table('permissions')
                                    //     ->where('name', 'customers-add')
                                    //     ->first();
                                    // $customer_add_permission_active = DB::table('role_has_permissions')
                                    //     ->where([['permission_id', $customer_add_permission->id], ['role_id', $role->id]])
                                    //     ->first();
                                    ?>
                                    @if ($customer_add_permission_active)
                                        <li id="customer-create-menu"><a
                                                href="{{ route('customer.create') }}">{{ trans('file.Add Customer') }}</a>
                                        </li>
                                    @endif
                                @endif

                                @if ($biller_index_permission_active)
                                    <li id="biller-list-menu"><a
                                            href="{{ route('biller.index') }}">{{ trans('file.Biller List') }}</a>
                                    </li>
                                    <?php
                                    // $biller_add_permission = DB::table('permissions')
                                    //     ->where('name', 'billers-add')
                                    //     ->first();
                                    // $biller_add_permission_active = DB::table('role_has_permissions')
                                    //     ->where([['permission_id', $biller_add_permission->id], ['role_id', $role->id]])
                                    //     ->first();
                                    ?>
                                    @if ($biller_add_permission_active)
                                        <li id="biller-create-menu"><a
                                                href="{{ route('biller.create') }}">{{ trans('file.Add Biller') }}</a>
                                        </li>
                                    @endif
                                @endif --}}

                                @if ($supplier_index_permission_active)
                                    <li id="supplier-list-menu"><a
                                            href="{{ route('supplier.index') }}">{{ trans('file.Supplier List') }}</a>
                                    </li>
                                    <?php
                                    $supplier_add_permission = DB::table('permissions')
                                        ->where('name', 'suppliers-add')
                                        ->first();
                                    $supplier_add_permission_active = DB::table('role_has_permissions')
                                        ->where([['permission_id', $supplier_add_permission->id], ['role_id', $role->id]])
                                        ->first();
                                    ?>
                                    @if ($supplier_add_permission_active)
                                        <li id="supplier-create-menu"><a
                                                href="{{ route('supplier.create') }}">{{ trans('file.Add Supplier') }}</a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Rapport Menu --}}
                    <?php
                    $profit_loss_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'profit-loss'], ['role_id', $role->id]])
                        ->first();
                    $best_seller_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'best-seller'], ['role_id', $role->id]])
                        ->first();
                    $warehouse_report_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'warehouse-report'], ['role_id', $role->id]])
                        ->first();
                    $warehouse_stock_report_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'warehouse-stock-report'], ['role_id', $role->id]])
                        ->first();
                    $product_report_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'product-report'], ['role_id', $role->id]])
                        ->first();
                    $daily_sale_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'daily-sale'], ['role_id', $role->id]])
                        ->first();
                    $monthly_sale_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'monthly-sale'], ['role_id', $role->id]])
                        ->first();
                    $daily_purchase_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'daily-purchase'], ['role_id', $role->id]])
                        ->first();
                    $monthly_purchase_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'monthly-purchase'], ['role_id', $role->id]])
                        ->first();
                    $purchase_report_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'purchase-report'], ['role_id', $role->id]])
                        ->first();
                    $sale_report_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'sale-report'], ['role_id', $role->id]])
                        ->first();
                    $payment_report_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'payment-report'], ['role_id', $role->id]])
                        ->first();
                    $product_qty_alert_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'product-qty-alert'], ['role_id', $role->id]])
                        ->first();
                    $user_report_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'user-report'], ['role_id', $role->id]])
                        ->first();
                    
                    $customer_report_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'customer-report'], ['role_id', $role->id]])
                        ->first();
                    $supplier_report_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'supplier-report'], ['role_id', $role->id]])
                        ->first();
                    $due_report_active = DB::table('permissions')
                        ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where([['permissions.name', 'due-report'], ['role_id', $role->id]])
                        ->first();
                    ?>
                    @if (
                        $profit_loss_active ||
                            $best_seller_active ||
                            $warehouse_report_active ||
                            $warehouse_stock_report_active ||
                            $product_report_active ||
                            $daily_sale_active ||
                            $monthly_sale_active ||
                            $daily_purchase_active ||
                            $monthly_purchase_active ||
                            $purchase_report_active ||
                            $sale_report_active ||
                            $payment_report_active ||
                            $product_qty_alert_active ||
                            $user_report_active ||
                            $customer_report_active ||
                            $supplier_report_active ||
                            $due_report_active)
                        <li><a href="#report" aria-expanded="false" data-toggle="collapse"> <i
                                    class="dripicons-document"></i><span>{{ trans('file.Reports') }}</span></a>
                            <ul id="report" class="collapse list-unstyled ">
                                @if ($profit_loss_active)
                                    <li id="profit-loss-report-menu">
                                        {!! Form::open(['route' => 'report.profitLoss', 'method' => 'post', 'id' => 'profitLoss-report-form']) !!}
                                        <input type="hidden" name="start_date"
                                            value="{{ date('Y-m') . '-' . '01' }}" />
                                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                                        <a id="profitLoss-link" href="">{{ trans('file.Summary Report') }}</a>
                                        {!! Form::close() !!}
                                    </li>
                                @endif
                                @if ($best_seller_active)
                                    <li id="best-seller-report-menu">
                                        <a href="{{ url('report/best_seller') }}">{{ trans('file.Best Seller') }}</a>
                                    </li>
                                @endif
                                @if ($product_report_active)
                                    <li id="product-report-menu">
                                        {!! Form::open(['route' => 'report.product', 'method' => 'get', 'id' => 'product-report-form']) !!}
                                        <input type="hidden" name="start_date"
                                            value="{{ date('Y-m') . '-' . '01' }}" />
                                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                                        <input type="hidden" name="warehouse_id" value="0" />
                                        <a id="report-link" href="">{{ trans('file.Product Report') }}</a>
                                        {!! Form::close() !!}
                                    </li>
                                @endif
                                @if ($daily_sale_active)
                                    <li id="daily-sale-report-menu">
                                        <a
                                            href="{{ url('report/daily_sale/' . date('Y') . '/' . date('m')) }}">{{ trans('file.Daily Sale') }}</a>
                                    </li>
                                @endif
                                @if ($monthly_sale_active)
                                    <li id="monthly-sale-report-menu">
                                        <a
                                            href="{{ url('report/monthly_sale/' . date('Y')) }}">{{ trans('file.Monthly Sale') }}</a>
                                    </li>
                                @endif
                                @if ($daily_purchase_active)
                                    <li id="daily-purchase-report-menu">
                                        <a
                                            href="{{ url('report/daily_purchase/' . date('Y') . '/' . date('m')) }}">{{ trans('file.Daily Purchase') }}</a>
                                    </li>
                                @endif
                                @if ($monthly_purchase_active)
                                    <li id="monthly-purchase-report-menu">
                                        <a
                                            href="{{ url('report/monthly_purchase/' . date('Y')) }}">{{ trans('file.Monthly Purchase') }}</a>
                                    </li>
                                @endif
                                @if ($sale_report_active)
                                    <li id="sale-report-menu">
                                        {!! Form::open(['route' => 'report.sale', 'method' => 'post', 'id' => 'sale-report-form']) !!}
                                        <input type="hidden" name="start_date"
                                            value="{{ date('Y-m') . '-' . '01' }}" />
                                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                                        <input type="hidden" name="warehouse_id" value="0" />
                                        <a id="sale-report-link" href="">{{ trans('file.Sale Report') }}</a>
                                        {!! Form::close() !!}
                                    </li>
                                @endif
                                @if ($payment_report_active)
                                    <li id="payment-report-menu">
                                        {!! Form::open(['route' => 'report.paymentByDate', 'method' => 'post', 'id' => 'payment-report-form']) !!}
                                        <input type="hidden" name="start_date"
                                            value="{{ date('Y-m') . '-' . '01' }}" />
                                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                                        <a id="payment-report-link"
                                            href="">{{ trans('file.Payment Report') }}</a>
                                        {!! Form::close() !!}
                                    </li>
                                @endif
                                @if ($purchase_report_active)
                                    <li id="purchase-report-menu">
                                        {!! Form::open(['route' => 'report.purchase', 'method' => 'post', 'id' => 'purchase-report-form']) !!}
                                        <input type="hidden" name="start_date"
                                            value="{{ date('Y-m') . '-' . '01' }}" />
                                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                                        <input type="hidden" name="warehouse_id" value="0" />
                                        <a id="purchase-report-link"
                                            href="">{{ trans('file.Purchase Report') }}</a>
                                        {!! Form::close() !!}
                                    </li>
                                @endif
                                @if ($warehouse_report_active)
                                    <li id="warehouse-report-menu">
                                        <a id="warehouse-report-link"
                                            href="">{{ trans('file.Warehouse Report') }}</a>
                                    </li>
                                @endif
                                @if ($warehouse_stock_report_active)
                                    <li id="warehouse-stock-report-menu">
                                        <a
                                            href="{{ route('report.warehouseStock') }}">{{ trans('file.Warehouse Stock Chart') }}</a>
                                    </li>
                                @endif
                                @if ($product_qty_alert_active)
                                    <li id="qtyAlert-report-menu">
                                        <a
                                            href="{{ route('report.qtyAlert') }}">{{ trans('file.Product Quantity Alert') }}</a>
                                    </li>
                                @endif
                                @if ($user_report_active)
                                    <li id="user-report-menu">
                                        <a id="user-report-link" href="">{{ trans('file.User Report') }}</a>
                                    </li>
                                @endif
                                @if ($customer_report_active)
                                    <li id="customer-report-menu">
                                        <a id="customer-report-link"
                                            href="">{{ trans('file.Customer Report') }}</a>
                                    </li>
                                @endif
                                @if ($supplier_report_active)
                                    <li id="supplier-report-menu">
                                        <a id="supplier-report-link"
                                            href="">{{ trans('file.Supplier Report') }}</a>
                                    </li>
                                @endif
                                @if ($due_report_active)
                                    <li id="due-report-menu">
                                        {!! Form::open(['route' => 'report.dueByDate', 'method' => 'post', 'id' => 'due-report-form']) !!}
                                        <input type="hidden" name="start_date"
                                            value="{{ date('Y-m') . '-' . '01' }}" />
                                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                                        <a id="due-report-link" href="">{{ trans('file.Due Report') }}</a>
                                        {!! Form::close() !!}
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Paramètres Menu --}}
                    <li><a href="#setting" aria-expanded="false" data-toggle="collapse"> <i
                                class="dripicons-gear"></i><span>{{ trans('file.settings') }}</span></a>
                        <ul id="setting" class="collapse list-unstyled ">
                            <?php
                            $send_notification_permission = DB::table('permissions')
                                ->where('name', 'send_notification')
                                ->first();
                            $send_notification_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $send_notification_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $warehouse_permission = DB::table('permissions')
                                ->where('name', 'warehouse')
                                ->first();
                            $warehouse_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $warehouse_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $customer_group_permission = DB::table('permissions')
                                ->where('name', 'customer_group')
                                ->first();
                            $customer_group_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $customer_group_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $brand_permission = DB::table('permissions')
                                ->where('name', 'brand')
                                ->first();
                            $brand_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $brand_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $unit_permission = DB::table('permissions')
                                ->where('name', 'unit')
                                ->first();
                            $unit_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $unit_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $currency_permission = DB::table('permissions')
                                ->where('name', 'currency')
                                ->first();
                            $currency_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $currency_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $tax_permission = DB::table('permissions')
                                ->where('name', 'tax')
                                ->first();
                            $tax_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $tax_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $general_setting_permission = DB::table('permissions')
                                ->where('name', 'general_setting')
                                ->first();
                            $general_setting_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $general_setting_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $backup_database_permission = DB::table('permissions')
                                ->where('name', 'backup_database')
                                ->first();
                            $backup_database_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $backup_database_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $mail_setting_permission = DB::table('permissions')
                                ->where('name', 'mail_setting')
                                ->first();
                            $mail_setting_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $mail_setting_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $sms_setting_permission = DB::table('permissions')
                                ->where('name', 'sms_setting')
                                ->first();
                            $sms_setting_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $sms_setting_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $create_sms_permission = DB::table('permissions')
                                ->where('name', 'create_sms')
                                ->first();
                            $create_sms_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $create_sms_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $pos_setting_permission = DB::table('permissions')
                                ->where('name', 'pos_setting')
                                ->first();
                            $pos_setting_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $pos_setting_permission->id], ['role_id', $role->id]])
                                ->first();
                            
                            $hrm_setting_permission = DB::table('permissions')
                                ->where('name', 'hrm_setting')
                                ->first();
                            $hrm_setting_permission_active = DB::table('role_has_permissions')
                                ->where([['permission_id', $hrm_setting_permission->id], ['role_id', $role->id]])
                                ->first();
                            ?>
                            @if ($role->id <= 2)
                                <li id="role-menu"><a
                                        href="{{ route('role.index') }}">{{ trans('file.Role & Permission') }}</a>
                                </li>
                            @endif
                            @if ($general_setting_permission_active)
                                <li id="general-setting-menu"><a
                                        href="{{ route('setting.general') }}">{{ trans('file.General Setting') }}</a>
                                </li>
                                <li id="livraison-menu"><a
                                        href="{{ route('setting.livraison') }}">{{ trans('file.Delivery Setting') }}</a>
                                </li>
                            @endif
                            <li id="user-menu"><a
                                    href="{{ route('user.profile', ['id' => Auth::id()]) }}">{{ trans('file.User Profile') }}</a>
                            </li>
                            {{-- @if ($send_notification_permission_active)
                                <li id="notification-menu">
                                    <a href="" id="send-notification">{{ trans('file.Send Notification') }}</a>
                                </li>
                            @endif --}}
                            @if ($warehouse_permission_active)
                                <li id="warehouse-menu"><a
                                        href="{{ route('warehouse.index') }}">{{ trans('file.Warehouse') }}</a>
                                </li>
                            @endif
                            @if ($customer_group_permission_active)
                                <li id="customer-group-menu"><a
                                        href="{{ route('customer_group.index') }}">{{ trans('file.Customer Group') }}</a>
                                </li>
                            @endif
                            @if ($brand_permission_active)
                                <li id="brand-menu"><a
                                        href="{{ route('brand.index') }}">{{ trans('file.Brand') }}</a></li>
                            @endif
                            @if ($unit_permission_active)
                                <li id="unit-menu"><a
                                        href="{{ route('unit.index') }}">{{ trans('file.Unit') }}</a>
                                </li>
                            @endif
                            @if ($currency_permission_active)
                                <li id="currency-menu"><a
                                        href="{{ route('currency.index') }}">{{ trans('file.Currency') }}</a></li>
                            @endif
                            @if ($tax_permission_active)
                                <li id="tax-menu"><a href="{{ route('tax.index') }}">{{ trans('file.Tax') }}</a>
                                </li>
                            @endif
                            {{-- @if ($create_sms_permission_active)
                                <li id="create-sms-menu"><a
                                        href="{{ route('setting.createSms') }}">{{ trans('file.Create SMS') }}</a>
                                </li>
                            @endif --}}
                            {{-- @if ($backup_database_permission_active)
                                <li><a href="{{ route('setting.backup') }}">{{ trans('file.Backup Database') }}</a>
                                </li>
                            @endif --}}
                            {{-- @if ($mail_setting_permission_active)
                                <li id="mail-setting-menu"><a
                                        href="{{ route('setting.mail') }}">{{ trans('file.Mail Setting') }}</a></li>
                            @endif
                            @if ($sms_setting_permission_active)
                                <li id="sms-setting-menu"><a
                                        href="{{ route('setting.sms') }}">{{ trans('file.SMS Setting') }}</a></li>
                            @endif --}}
                            {{-- @if ($pos_setting_permission_active)
                                <li id="pos-setting-menu"><a href="{{ route('setting.pos') }}">POS
                                        {{ trans('file.settings') }}</a></li>
                            @endif --}}
                            {{-- @if ($hrm_setting_permission_active)
                                <li id="hrm-setting-menu"><a href="{{ route('setting.hrm') }}">
                                        {{ trans('file.HRM Setting') }}</a></li>
                            @endif --}}
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- navbar-->
    <header class="header">
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-holder d-flex align-items-center justify-content-between">
                    <a id="toggle-btn" href="#" class="menu-btn"><i class="fa fa-bars"> </i></a>
                    <span class="brand-big">
                        @if ($general_setting->site_logo)
                            <a href="{{ url('/') }}"><img
                                    src="{{ url('public/logo', $general_setting->site_logo) }}" width="90"></a>
                        @else
                            <a href="{{ url('/') }}">
                                <h1 class="d-inline">{{ $general_setting->site_title }}</h1>
                            </a>
                        @endif
                    </span>

                    <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                        <?php
                        $add_permission = DB::table('permissions')
                            ->where('name', 'sales-add')
                            ->first();
                        $add_permission_active = DB::table('role_has_permissions')
                            ->where([['permission_id', $add_permission->id], ['role_id', $role->id]])
                            ->first();
                        
                        $empty_database_permission = DB::table('permissions')
                            ->where('name', 'empty_database')
                            ->first();
                        $empty_database_permission_active = DB::table('role_has_permissions')
                            ->where([['permission_id', $empty_database_permission->id], ['role_id', $role->id]])
                            ->first();
                        ?>
                        {{-- @if ($add_permission_active)
                <li class="nav-item"><a class="dropdown-item btn-pos btn-sm" href="{{route('sale.pos')}}"><i class="dripicons-shopping-bag"></i><span> POS</span></a></li>
                @endif --}}
                        @impersonate
                            <li class="nav-item"><a href="{{ route('impersonate.destroy') }}"><i
                                        class="dripicons-clockwise"></i> <span>Stop</span></a></li>
                            <li class="dividers"></li>
                        @endimpersonate
                        <li class="nav-item"><a id="btnFullscreen"><i class="dripicons-expand"></i></a></li>
                        <li class="dividers"></li>
                        {{-- @if (\Auth::user()->role_id <= 2)
                  <li class="nav-item"><a href="{{route('cashRegister.index')}}" title="{{trans('file.Cash Register List')}}"><i class="dripicons-archive"></i></a></li>
                @endif --}}
                        @if ($product_qty_alert_active)
                            @if ($alert_product + count(\Auth::user()->unreadNotifications) > 0)
                                <li class="nav-item" id="notification-icon">
                                    <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-item"><i
                                            class="dripicons-bell"></i><span
                                            class="badge badge-danger notification-number">{{ $alert_product + count(\Auth::user()->unreadNotifications) }}</span>
                                    </a>
                                    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default notifications"
                                        user="menu">
                                        <li class="notifications">
                                            <a href="{{ route('report.qtyAlert') }}" class="btn btn-link">
                                                {{ $alert_product }} product exceeds alert quantity</a>
                                        </li>
                                        @foreach (\Auth::user()->unreadNotifications as $key => $notification)
                                            <li class="notifications">
                                                <a href="#"
                                                    class="btn btn-link">{{ $notification->data['message'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="dividers"></li>
                            @elseif(count(\Auth::user()->unreadNotifications) > 0)
                                <li class="nav-item" id="notification-icon">
                                    <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-item"><i
                                            class="dripicons-bell"></i><span
                                            class="badge badge-danger notification-number">{{ count(\Auth::user()->unreadNotifications) }}</span>
                                    </a>
                                    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default notifications"
                                        user="menu">
                                        @foreach (\Auth::user()->unreadNotifications as $key => $notification)
                                            <li class="notifications">
                                                <a href="#"
                                                    class="btn btn-link">{{ $notification->data['message'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="dividers"></li>
                            @endif
                        @endif
                        <li class="nav-item">
                            <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-item"><i
                                    class="dripicons-web"></i>
                                <span>{{ __('file.language') }}</span> <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                user="menu">
                                <li>
                                    <a href="{{ url('language_switch/ar') }}" class="btn btn-link"> العربية</a>
                                </li>
                                <li>
                                    <a href="{{ url('language_switch/fr') }}" class="btn btn-link"> Français</a>
                                </li>
                                <li>
                                    <a href="{{ url('language_switch/en') }}" class="btn btn-link"> English</a>
                                </li>
                                {{-- <li>
                            <a href="{{ url('language_switch/es') }}" class="btn btn-link"> Español</a>
                          </li>                          
                          <li>
                            <a href="{{ url('language_switch/pt_BR') }}" class="btn btn-link"> Portuguese</a>
                          </li>                          
                          <li>
                            <a href="{{ url('language_switch/de') }}" class="btn btn-link"> Deutsche</a>
                          </li>
                          <li>
                            <a href="{{ url('language_switch/id') }}" class="btn btn-link"> Malay</a>
                          </li>
                          <li>
                            <a href="{{ url('language_switch/hi') }}" class="btn btn-link"> हिंदी</a>
                          </li>
                          <li>
                            <a href="{{ url('language_switch/vi') }}" class="btn btn-link"> Tiếng Việt</a>
                          </li>
                          <li>
                            <a href="{{ url('language_switch/ru') }}" class="btn btn-link"> русский</a>
                          </li>
                          <li>
                            <a href="{{ url('language_switch/bg') }}" class="btn btn-link"> български</a>
                          </li>
                          <li>
                            <a href="{{ url('language_switch/tr') }}" class="btn btn-link"> Türk</a>
                          </li>
                          <li>
                            <a href="{{ url('language_switch/it') }}" class="btn btn-link"> Italiano</a>
                          </li>
                          <li>
                            <a href="{{ url('language_switch/nl') }}" class="btn btn-link"> Nederlands</a>
                          </li>
                          <li>
                            <a href="{{ url('language_switch/lao') }}" class="btn btn-link"> Lao</a>
                          </li> --}}
                            </ul>
                        </li>
                        <li class="dividers"></li>
                        {{-- @if (Auth::user()->role_id != 5)
                            <li class="nav-item">
                                <a class="dropdown-item" href="{{ url('read_me') }}" target="_blank"><i
                                        class="dripicons-information"></i> {{ trans('file.Help') }}</a>
                            </li>
                        @endif --}}
                        <li class="nav-item">
                            <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-item"><i
                                    class="dripicons-user"></i>
                                <span>{{ ucfirst(Auth::user()->name) }}</span> <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                user="menu">
                                <li>
                                    <a href="{{ route('user.profile', ['id' => Auth::id()]) }}"><i
                                            class="dripicons-user"></i> {{ trans('file.profile') }}</a>
                                </li>
                                @if ($general_setting_permission_active)
                                    <li>
                                        <a href="{{ route('setting.general') }}"><i class="dripicons-gear"></i>
                                            {{ trans('file.settings') }}</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ url('my-transactions/' . date('Y') . '/' . date('m')) }}"><i
                                            class="dripicons-swap"></i> {{ trans('file.My Transaction') }}</a>
                                </li>
                                @if (Auth::user()->role_id != 5)
                                    {{-- <li>
                                        <a href="{{ url('holidays/my-holiday/' . date('Y') . '/' . date('m')) }}"><i
                                                class="dripicons-vibrate"></i> {{ trans('file.My Holiday') }}</a> --}}
                        </li>
                        @endif

                        @if ($backup_database_permission_active)
                            <li>
                                <a href="{{ route('setting.backup') }}"><i class="dripicons-archive"></i>
                                    {{ trans('file.Backup Database') }}</a>
                            </li>
                        @endif

                        @if (Auth::user()->role_id == 1)
                            <li>
                                <a href="{{ route('clear-views') }}"><i class="dripicons-lock"></i>
                                    {{ trans('file.Clear Views') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('clear-cache') }}"><i class="dripicons-lock-open"></i>
                                    {{ trans('file.Clear Cache') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('gain-process') }}"><i class="dripicons-article"></i>
                                    {{ trans('file.Gain Table') }}</a>
                            </li>
                        @endif

                        {{-- @if ($empty_database_permission_active)
                                    <li>
                                        <a onclick="return confirm('Are you sure want to delete? If you do this all of your data will be lost.')"
                                            href="{{ route('setting.emptyDatabase') }}"><i
                                                class="dripicons-stack"></i> {{ trans('file.Empty Database') }}</a>
                                    </li>
                                @endif --}}
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();"><i
                                    class="dripicons-power"></i>
                                {{ trans('file.logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                    </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="page">

        <!-- discount modal -->
        <div id="withdraw-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Demander un retrait') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $lims_retrait_data = DB::table('users')
                            ->where('id', Auth::id())
                            ->first();
                        
                        $lims_sales_data = DB::table('sales')
                            ->where([['user_id', Auth::id()], ['delivery_status', 4]])
                            ->whereNull('withdrawal_id')
                            ->get();
                        
                        $total_gains = 0;
                        
                        foreach ($lims_sales_data as $key => $sale) {
                            $lims_gains_data = DB::table('gains')
                                ->where('sale_id', $sale->id)
                                ->first();
                            $total_gains += $lims_gains_data->grand_total + $lims_gains_data->total_discount - $lims_gains_data->total_original_price - $lims_gains_data->total_livraison;
                        }
                        
                        $lims_min_retrait = DB::table('general_settings')->first();
                        
                        $last_date_r = Carbon\Carbon::createFromFormat('d/m/Y', $lims_retrait_data->date_r)->format('m/d/Y');
                        
                        $diff = now()->diffInDays(Carbon\Carbon::parse($last_date_r));
                        ?>
                        @if ($total_gains >= $lims_min_retrait->min_withdraw)
                            @if (!$lims_retrait_data->demande_r)
                                @if ($diff >= 2)
                                    <p style="text-align: center;">
                                        {{ trans('file.Confirmer votre demande de retrait ? ') }}</p>
                                    <p style="text-align: center;">
                                        {{ trans('file.Le montant total des gains  : ') }} <b
                                            style="color: red;">{{ number_format((float) $total_gains, 2, '.', ' ') }}
                                            DHS</b></p>
                                    {!! Form::open(['route' => ['withdraw.demander-retrait', 'id' => Auth::id()], 'method' => 'post']) !!}
                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-primary">{{ trans('file.submit') }}</button>
                                    </div>
                                    {{ Form::close() }}
                                @else
                                    <p style="text-align: center;">{{ trans('file.Il vous reste') }} <b
                                            style="color: red;">{{ 2 - $diff }} {{ trans('file.jours') }}</b>
                                        {{ trans('file.avant de demander un retrait !') }}</p>
                                @endif
                            @else
                                <b style="color: red">La dernière demande n'est pas encore validée !</b>
                            @endif
                        @else
                            <p style="text-align: center;">
                                {{ trans('file.Le montant total des gains doit être supérieur ou égal à : ') }} <b
                                    style="color: red;">{{ number_format((float) $lims_min_retrait->min_withdraw, 2, '.', ' ') }}
                                    DHS</b></p>
                            <p style="text-align: center;">{{ trans('file.Vous avez : ') }} <b
                                    style="color: red;">{{ number_format((float) $total_gains, 2, '.', ' ') }}
                                    DHS</b>
                            </p>
                        @endif
                        
                        {{-- <div class="row">
                            <?php
                            $lims_user_list = DB::table('users')
                                ->where('is_active', true)
                                ->get();
                            ?>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label>{{ trans('file.User') }} *</label>
                                <select name="discount_user_id" class="selectpicker form-control" required
                                    data-live-search="true" title="Select user...">
                                    @foreach ($lims_user_list as $user)
                                        <option value="{{ $user->id }}">
                                            {{ strtoupper($user->last_name) . ' ' . ucfirst($user->first_name) . ' (' . $user->name . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 form-group">
                                <label>{{ trans('file.Reference no') }} *</label>
                                <select name="discount_reference_no" id="discount_reference_no"
                                    class="selectpicker form-control" required data-live-search="true"
                                    title="Select reference no...">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 form-group">
                                <label>{{ trans('file.Montant de la remise') }} *</label>
                                <input type="number" min="1" name="total_discount" step="any" required
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }} --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- end discount modal -->

        <!-- discount modal -->
        <div id="discount-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Discount') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'sale.add-discount', 'method' => 'post']) !!}
                        <div class="row">
                            <?php
                            $lims_user_list = DB::table('users')
                                ->where('is_active', true)
                                ->get();
                            ?>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label>{{ trans('file.User') }} *</label>
                                <select name="discount_user_id" class="selectpicker form-control" required
                                    data-live-search="true" title="Select user...">
                                    @foreach ($lims_user_list as $user)
                                        <option value="{{ $user->id }}">
                                            {{ strtoupper($user->last_name) . ' ' . ucfirst($user->first_name) . ' (' . $user->name . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 form-group">
                                <label>{{ trans('file.Reference no') }} *</label>
                                <select name="discount_reference_no" id="discount_reference_no"
                                    class="selectpicker form-control" required data-live-search="true"
                                    title="Select reference no...">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 form-group">
                                <label>{{ trans('file.Montant de la remise') }} *</label>
                                <input type="number" min="1" name="total_discount" step="any" required
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end discount modal -->

        <!-- facturation referral modal -->
        <div id="facturation-referral-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Facturation pour ') }} <span
                                class="span-title"></span></h5>
                        <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close"
                            class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
        {{-- <div id="facturation-referral-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Facturation Referral') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'user.add-refferal', 'method' => 'post']) !!}
                        <div class="row">
                            <?php
                            // $lims_user_list = DB::table('users')
                            //     ->where('is_active', true)
                            //     ->get();
                            ?>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label>{{ trans('file.User') }} *</label>
                                <select name="referral_user_id" class="selectpicker form-control" required
                                    data-live-search="true" title="Select user...">
                                    @foreach ($lims_user_list as $user)
                                        <option value="{{ $user->id }}">
                                            {{ strtoupper($user->last_name) . ' ' . ucfirst($user->first_name) . ' (' . $user->name . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="ref_id"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 form-group">
                                <label>{{ trans('file.Referral') }} *</label>
                                <select name="referral_id" class="selectpicker form-control" required
                                    data-live-search="true" title="Select referral...">
                                    @foreach ($lims_user_list as $user)
                                        <option value="{{ $user->id }}">
                                            {{ strtoupper($user->last_name) . ' ' . ucfirst($user->first_name) . ' (' . $user->name . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- end add referral modal -->

        <!-- add referral modal -->
        <div id="referral-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Referral') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'user.add-refferal', 'method' => 'post']) !!}
                        <div class="row">
                            <?php
                            $lims_user_list = DB::table('users')
                                ->where('is_active', true)
                                ->get();
                            ?>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label>{{ trans('file.Vendeur') }} *</label>
                                <select name="referral_user_id" class="selectpicker form-control" required
                                    data-live-search="true" title="{{ trans('file.Select saler...') }}">
                                    @foreach ($lims_user_list as $user)
                                        <option value="{{ $user->id }}">
                                            {{ strtoupper($user->last_name) . ' ' . ucfirst($user->first_name) . ' (' . $user->name . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="ref_id"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 form-group">
                                <label>{{ trans('file.Referral') }} *</label>
                                <select name="referral_id" class="selectpicker form-control" required
                                    data-live-search="true" title="{{ trans('file.Select referral...') }}">
                                    @foreach ($lims_user_list as $user)
                                        <option value="{{ $user->id }}">
                                            {{ strtoupper($user->last_name) . ' ' . ucfirst($user->first_name) . ' (' . $user->name . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end add referral modal -->

        <!-- notification modal -->
        <div id="notification-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Send Notification') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'notifications.store', 'method' => 'post']) !!}
                        <div class="row">
                            <?php
                            $lims_user_list = DB::table('users')
                                ->where([['is_active', true], ['id', '!=', \Auth::user()->id]])
                                ->get();
                            ?>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.User') }} *</label>
                                <select name="user_id" class="selectpicker form-control" required
                                    data-live-search="true" title="Select user...">
                                    @foreach ($lims_user_list as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name . ' (' . $user->email . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>{{ trans('file.Message') }} *</label>
                                <textarea rows="5" name="message" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end notification modal -->

        <!-- expense modal -->
        <div id="expense-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Expense') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'expenses.store', 'method' => 'post']) !!}
                        <?php
                        $lims_expense_category_list = DB::table('expense_categories')
                            ->where('is_active', true)
                            ->get();
                        if (Auth::user()->role_id > 2) {
                            $lims_warehouse_list = DB::table('warehouses')
                                ->where([['is_active', true], ['id', Auth::user()->warehouse_id]])
                                ->get();
                        } else {
                            $lims_warehouse_list = DB::table('warehouses')
                                ->where('is_active', true)
                                ->get();
                        }
                        $lims_account_list = \App\Account::where('is_active', true)->get();
                        
                        ?>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.Expense Category') }} *</label>
                                <select name="expense_category_id" class="selectpicker form-control" required
                                    data-live-search="true" title="Select Expense Category...">
                                    @foreach ($lims_expense_category_list as $expense_category)
                                        <option value="{{ $expense_category->id }}">
                                            {{ $expense_category->name . ' (' . $expense_category->code . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.Warehouse') }} *</label>
                                <select name="warehouse_id" class="selectpicker form-control" required
                                    data-live-search="true" title="Select Warehouse...">
                                    @foreach ($lims_warehouse_list as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.Amount') }} *</label>
                                <input type="number" name="amount" step="any" required class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
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
                        </div>
                        <div class="form-group">
                            <label>{{ trans('file.Note') }}</label>
                            <textarea name="note" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end expense modal -->

        <!-- ticket modal -->
        <div id="ticket-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Ticket') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['route' => 'tickets.store', 'method' => 'post']) !!}
                        <?php
                        $categories = DB::table('cat_tickets')->get();
                        ?>
                        <div class="row">
                            <div class="col-md-12 col-md-offset-1">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        @if (session('status'))
                                            <div class="alert alert-success">
                                                {{ session('status') }}
                                            </div>
                                        @endif
                                        <form class="form-horizontal" role="form" method="POST">
                                            {!! csrf_field() !!}
                                            <div
                                                class="form-group{{ $errors->has('priority') ? ' has-error' : ' ' }}{{ $errors->has('priority') ? ' has-error' : ' ' }}">
                                                <label for="category" class="col-md-4 control-label">Category</label>
                                                <div class="col-md-6">
                                                    <select id="category" type="category" class="form-control"
                                                        name="category" required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('category'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('category') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                                                <label for="message" class="col-md-4 control-label">Message</label>
                                                <div class="col-md-12">
                                                    <textarea rows="5" id="message" class="form-control" name="message" required></textarea>
                                                    @if ($errors->has('message'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('message') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-btn fa-ticket"></i> Envoyer réclamation
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end ticket modal -->

        <!-- account modal -->
        <div id="account-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Account') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'accounts.store', 'method' => 'post']) !!}
                        <div class="form-group">
                            <label>{{ trans('file.Account No') }} *</label>
                            <input type="text" name="account_no" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ trans('file.name') }} *</label>
                            <input type="text" name="name" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ trans('file.Initial Balance') }}</label>
                            <input type="number" name="initial_balance" step="any" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ trans('file.Note') }}</label>
                            <textarea name="note" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end account modal -->

        <!-- account statement modal -->
        <div id="account-statement-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Account Statement') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'accounts.statement', 'method' => 'post']) !!}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label> {{ trans('file.Account') }}</label>
                                <select class="form-control selectpicker" name="account_id">
                                    @foreach ($lims_account_list as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}
                                            [{{ $account->account_no }}]</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-6 form-group">
                                <label> {{ trans('file.Type') }}</label>
                                <input type="text" name="type" class="form-control" required disabled value="{{ trans('file.All') }}"/>
                                <select class="form-control selectpicker" name="type">
                                    <option value="0">{{ trans('file.All') }}</option>
                                    <option value="1">{{ trans('file.Debit') }}</option>
                                    <option value="2">{{ trans('file.Credit') }}</option>
                                </select>
                            </div> --}}
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.Choose Your Date') }}</label>
                                <div class="input-group">
                                    <input type="text" class="daterangepicker-field form-control" required />
                                    <input type="hidden" name="start_date"
                                        value="{{ date('Y-m') . '-' . '01' }}" />
                                    <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end account statement modal -->

        <!-- warehouse modal -->
        <div id="warehouse-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Warehouse Report') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'report.warehouse', 'method' => 'post']) !!}
                        <?php
                        $lims_warehouse_list = DB::table('warehouses')
                            ->where('is_active', true)
                            ->get();
                        ?>
                        <div class="form-group">
                            <label>{{ trans('file.Warehouse') }} *</label>
                            <select name="warehouse_id" class="selectpicker form-control" required
                                data-live-search="true" id="warehouse-id" title="Select warehouse...">
                                @foreach ($lims_warehouse_list as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end warehouse modal -->

        <!-- user modal -->
        <div id="user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.User Report') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'report.user', 'method' => 'post']) !!}
                        <?php
                        $lims_user_list = DB::table('users')
                            ->where('is_active', true)
                            ->get();
                        ?>
                        <div class="form-group">
                            <label>{{ trans('file.User') }} *</label>
                            <select name="user_id" class="selectpicker form-control" required data-live-search="true"
                                id="user-id" title="Select user...">
                                @foreach ($lims_user_list as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name . ' (' . $user->phone . ')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end user modal -->

        <!-- customer modal -->
        <div id="customer-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Customer Report') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'report.customer', 'method' => 'post']) !!}
                        <?php
                        $lims_customer_list = DB::table('customers')
                            ->where('is_active', true)
                            ->get();
                        ?>
                        <div class="form-group">
                            <label>{{ trans('file.customer') }} *</label>
                            <select name="customer_id" class="selectpicker form-control" required
                                data-live-search="true" id="customer-id" title="Select customer...">
                                @foreach ($lims_customer_list as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name . ' (' . $customer->phone_number . ')' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end customer modal -->

        <!-- supplier modal -->
        <div id="supplier-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Supplier Report') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'report.supplier', 'method' => 'post']) !!}
                        <?php
                        $lims_supplier_list = DB::table('suppliers')
                            ->where('is_active', true)
                            ->get();
                        ?>
                        <div class="form-group">
                            <label>{{ trans('file.Supplier') }} *</label>
                            <select name="supplier_id" class="selectpicker form-control" required
                                data-live-search="true" id="supplier-id" title="Select Supplier...">
                                @foreach ($lims_supplier_list as $supplier)
                                    <option value="{{ $supplier->id }}">
                                        {{ $supplier->name . ' (' . $supplier->phone_number . ')' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end supplier modal -->

        <div style="display:none" id="content" class="animate-bottom">
            @yield('content')
        </div>

        <footer class="main-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <p>{{ date('Y') }} &copy; {{ $general_setting->site_title }} |
                            {{ trans('file.Developed') }} {{ trans('file.By') }} <span
                                class="external">{{ $general_setting->developed_by }}</span></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    @yield('scripts')
    <script type="text/javascript">
        var alert_product = <?php echo json_encode($alert_product); ?>;

        if ($(window).outerWidth() > 1199) {
            $('nav.side-navbar').removeClass('shrink');
        }

        function myFunction() {
            setTimeout(showPage, 150);

            //alert("clicking");
        }

        $(window).on('load', function() {
            $('#toggle-btn').trigger('click');
        });

        $(document).ready(function() {
            $('select[name="discount_user_id"]').on('change', function() {
                $("#discount_reference_no").empty();
                $("#discount_reference_no").val('default');
                $("#discount_reference_no").selectpicker("refresh");
                var id = $(this).val();
                var APP_URL = {!! json_encode(url('/')) !!}
                $.ajax({
                    url: APP_URL + '/sales/getsales/' + id,
                    type: "GET",
                    success: function(data) {
                        $.each(data, function(index) {
                            var selectOptions = new Option(data[index], data[index]);
                            $(selectOptions).html(data[index]);
                            $("#discount_reference_no").append(selectOptions);
                        });
                        $("#discount_reference_no").selectpicker('refresh');
                    },
                    error: function(data) {
                        alert("Erreur lors du remplissage des ventes !");
                    }
                });
            });
        });

        /*window.onload = (event) =>{
            console.log('Page Loaded');

        };*/

        function showPage() {
            document.getElementById("loader").style.display = "none";
            document.getElementById("content").style.display = "block";
        }

        $("div.alert").delay(3000).slideUp(750);

        function confirmDelete() {
            if (confirm("Are you sure want to delete?")) {
                return true;
            }
            return false;
        }

        $("li#notification-icon").on("click", function(argument) {
            $.get('notifications/mark-as-read', function(data) {
                $("span.notification-number").text(alert_product);
            });
        });

        $("a#add-expense").click(function(e) {
            e.preventDefault();
            $('#expense-modal').modal();
        });

        $("a#send-notification").click(function(e) {
            e.preventDefault();
            $('#notification-modal').modal();
        });

        $("a#add-account").click(function(e) {
            e.preventDefault();
            $('#account-modal').modal();
        });

        $("a#add-ticket").click(function(e) {
            e.preventDefault();
            $('#ticket-modal').modal();
        });

        $("a#account-statement").click(function(e) {
            e.preventDefault();
            $('#account-statement-modal').modal();
        });

        $("a#profitLoss-link").click(function(e) {
            e.preventDefault();
            $("#profitLoss-report-form").submit();
        });

        $("a#report-link").click(function(e) {
            e.preventDefault();
            $("#product-report-form").submit();
        });

        $("a#purchase-report-link").click(function(e) {
            e.preventDefault();
            $("#purchase-report-form").submit();
        });

        $("a#sale-report-link").click(function(e) {
            e.preventDefault();
            $("#sale-report-form").submit();
        });

        $("a#payment-report-link").click(function(e) {
            e.preventDefault();
            $("#payment-report-form").submit();
        });

        $("a#warehouse-report-link").click(function(e) {
            e.preventDefault();
            $('#warehouse-modal').modal();
        });

        $("a#user-report-link").click(function(e) {
            e.preventDefault();
            $('#user-modal').modal();
        });

        $("a#customer-report-link").click(function(e) {
            e.preventDefault();
            $('#customer-modal').modal();
        });

        $("a#supplier-report-link").click(function(e) {
            e.preventDefault();
            $('#supplier-modal').modal();
        });

        $("a#due-report-link").click(function(e) {
            e.preventDefault();
            $("#due-report-form").submit();
        });

        $(".daterangepicker-field").daterangepicker({
            callback: function(startDate, endDate, period) {
                var start_date = startDate.format('YYYY-MM-DD');
                var end_date = endDate.format('YYYY-MM-DD');
                var title = start_date + ' To ' + end_date;
                $(this).val(title);
                $('#account-statement-modal input[name="start_date"]').val(start_date);
                $('#account-statement-modal input[name="end_date"]').val(end_date);
            }
        });

        $('.selectpicker').selectpicker({
            style: 'btn-link',
        });
    </script>
</body>

</html>
