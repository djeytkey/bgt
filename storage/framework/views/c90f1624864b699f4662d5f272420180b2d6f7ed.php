<?php $__env->startSection('content'); ?>
<?php if(session()->has('not_permitted')): ?>
<div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div>
<?php endif; ?>
<?php if(session()->has('message')): ?>
<div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('message')); ?></div>
<?php endif; ?>
<div class="row">
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="brand-text float-left mt-4">
                <h3><?php echo e(ucfirst(trans('file.welcome'))); ?> <span><?php echo e(strtoupper(Auth::user()->last_name)); ?>

                        <?php echo e(ucfirst(Auth::user()->first_name)); ?></span> </h3>
            </div>
            <div class="filter-toggle btn-group">
                <button class="btn btn-secondary date-btn" data-start_date="<?php echo e(date('Y-m-d')); ?>" data-end_date="<?php echo e(date('Y-m-d')); ?>"><?php echo e(trans('file.Today')); ?></button>
                <button class="btn btn-secondary date-btn" data-start_date="<?php echo e(date('Y-m-d', strtotime(' -7 day'))); ?>" data-end_date="<?php echo e(date('Y-m-d')); ?>"><?php echo e(trans('file.Last 7 Days')); ?></button>
                <button class="btn btn-secondary date-btn active" data-start_date="<?php echo e(date('Y') . '-' . date('m') . '-' . '01'); ?>" data-end_date="<?php echo e(date('Y-m-d')); ?>"><?php echo e(trans('file.This Month')); ?></button>
                <button class="btn btn-secondary date-btn" data-start_date="<?php echo e(date('Y') . '-01' . '-01'); ?>" data-end_date="<?php echo e(date('Y') . '-12' . '-31'); ?>"><?php echo e(trans('file.This Year')); ?></button>
            </div>
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
                            <div class="name"><strong style="color: #733686"><?php echo e(trans('file.Total Orders')); ?></strong></div>
                            <div class="count-number total-order-data"><strong style="color: #733686"><?php echo e(number_format((float) $total_sales, 0, '.', ' ')); ?></strong>
                            </div>
                        </div>
                    </div>
                    <!-- Count item widget-->
                    <div class="col-sm-2">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-return" style="color: #00c689"></i></div>
                            <div class="name"><strong style="color: #00c689"><?php echo e(trans('file.Delivered')); ?></strong>
                            </div>
                            <div class="count-number delivered-data"><strong style="color: #00c689"><?php echo e(number_format((float) $livre, 0, '.', ' ')); ?></strong>
                            </div>
                        </div>
                    </div>
                    <!-- Count item widget-->
                    <div class="col-sm-2">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-media-loop" style="color: #ff8952"></i></div>
                            <div class="name"><strong style="color: #ff8952"><?php echo e(trans('file.In progress')); ?></strong>
                            </div>
                            <div class="count-number in-progress-data"><strong style="color: #ff8952"><?php echo e(number_format((float) $en_cours, 0, '.', ' ')); ?></strong>
                            </div>
                        </div>
                    </div>
                    <!-- Count item widget-->
                    <div class="col-sm-2">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-media-loop" style="color: red"></i></div>
                            <div class="name"><strong style="color: red"><?php echo e(trans('file.Returned')); ?></strong></div>
                            <div class="count-number in-progress-data"><strong style="color: red"><?php echo e(number_format((float) $returned, 0, '.', ' ')); ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-media-loop" style="color: red"></i></div>
                            <div class="name"><strong style="color: red"><?php echo e(trans('file.Refused')); ?></strong></div>
                            <div class="count-number in-progress-data"><strong style="color: red"><?php echo e(number_format((float) $refused, 0, '.', ' ')); ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-media-loop" style="color: red"></i></div>
                            <div class="name"><strong style="color: red"><?php echo e(trans('file.New Sales')); ?></strong></div>
                            <div class="count-number in-progress-data"><strong style="color: red"><?php echo e(number_format((float) $new_sale, 0, '.', ' ')); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Ligne des Retraits -->
                <div class="row">
                    <!-- Count item widget-->
                    <div class="col-sm-<?php echo e($profit_row); ?>">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                            <div class="name"><strong style="color: #297ff9"><?php echo e(trans('file.Profit')); ?></strong>
                            </div>
                            <div class="count-number total-profit-data">
                                <?php echo e(number_format((float) $profit, 2, '.', ' ')); ?>

                            </div>
                        </div>
                    </div>
                    <!-- Count item widget-->
                    <div class="col-sm-<?php echo e($profit_row); ?>">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                            <div class="name"><strong style="color: #297ff9"><?php echo e(trans('file.paid')); ?></strong></div>
                            <div class="count-number profit-claimed-data">
                                <?php echo e(number_format((float) $total_withdraws, 2, '.', ' ')); ?>

                                <span style="display: inline-flex; font-size: 0.65em;">(<?php echo e($demande_withdraws); ?>)</span>
                            </div>
                        </div>
                    </div>
                    <!-- Count item widget-->
                    <div class="col-sm-<?php echo e($profit_row); ?>">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                            <div class="name"><strong style="color: #297ff9"><?php echo e(trans('file.Remaining')); ?></strong>
                            </div>
                            <div class="count-number profit-remain-data">
                                <?php echo e(number_format((float) $reste, 2, '.', ' ')); ?>

                            </div>
                        </div>
                    </div>
                    <?php if(Auth::user()->role_id == 1): ?>
                    <!-- Count item widget-->
                    <div class="col-sm-<?php echo e($profit_row); ?>">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                            <div class="name"><strong style="color: #297ff9"><?php echo e(trans('file.Remaining')); ?></strong></div>
                            <div class="count-number profit-remain-data">
                                <?php echo e(number_format((float) $reste, 2, '.', ' ')); ?>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <!--Ligne des parrains -->
                <?php if($is_referral): ?>
                <div class="row">
                    <!-- Count item widget-->
                    <div class="col-sm-<?php echo e($profit_row); ?>">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                            <div class="name"><strong style="color: #297ff9"><?php echo e(trans('file.Profit')); ?></strong>
                            </div>
                            <div class="count-number total-profit-data">
                                <?php echo e(number_format((float) $total_v_referral, 2, '.', ' ')); ?>

                            </div>
                        </div>
                    </div>
                    <!-- Count item widget-->
                    <div class="col-sm-<?php echo e($profit_row); ?>">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                            <div class="name"><strong style="color: #297ff9"><?php echo e(trans('file.paid')); ?></strong>
                            </div>
                            <div class="count-number profit-claimed-data">
                                <?php echo e(number_format((float) $total_w_referral, 2, '.', ' ')); ?>

                            </div>
                        </div>
                    </div>
                    <!-- Count item widget-->
                    <div class="col-sm-<?php echo e($profit_row); ?>">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                            <div class="name"><strong style="color: #297ff9"><?php echo e(trans('file.Remaining')); ?></strong>
                            </div>
                            <div class="count-number profit-remain-data">
                                <?php echo e(number_format((float) $total_r_referral, 2, '.', ' ')); ?>

                            </div>
                        </div>
                    </div>
                    <?php if(Auth::user()->role_id == 1): ?>
                    <!-- Count item widget-->
                    <div class="col-sm-<?php echo e($profit_row); ?>">
                        <div class="wrapper count-title text-center">
                            <div class="icon"><i class="dripicons-trophy" style="color: #297ff9"></i></div>
                            <div class="name"><strong style="color: #297ff9"><?php echo e(trans('file.Remaining')); ?></strong></div>
                            <div class="count-number profit-remain-data">
                                <?php echo e(number_format((float) $total_v_referral, 2, '.', ' ')); ?>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4><?php echo e(trans('file.Best Seller').' '.date('F')); ?></h4>
                                <div class="right-column">
                                    <div class="badge badge-primary"><?php echo e(trans('file.top')); ?> 10</div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>SL No</th>
                                            <th><?php echo e(trans('file.Product Details')); ?></th>
                                            <th><?php echo e(trans('file.qty')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $best_selling_qty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $product = DB::table('products')->find($sale->product_id); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td><?php echo e($product->name); ?>&nbsp;&nbsp;[<?php echo e($product->code); ?>]</td>
                                            <td><?php echo e($sale->sold_qty); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4><?php echo e(trans('file.Best Saler').' '.date('F')); ?></h4>
                                <div class="right-column">
                                    <div class="badge badge-primary"><?php echo e(trans('file.top')); ?> 10</div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>SL No</th>
                                            <th><?php echo e(trans('file.Vendeur')); ?></th>
                                            <th><?php echo e(trans('file.Montant')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $best_saler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$saler): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $vendeur = DB::table('users')->find($saler->user_id); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td><?php echo e(strtoupper($vendeur->last_name)); ?>&nbsp;<?php echo e(ucfirst($vendeur->first_name)); ?></td>
                                            <td><?php echo e($saler->t_gain); ?> DH</td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $("ul#dashboard").siblings('a').attr('aria-expanded','true');
    $("ul#dashboard").addClass("show");
    $("ul#dashboard #dashboard-2").addClass("active");

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

    $(".date-btn").on("click", function() {
        $(".date-btn").removeClass("active");
        $(this).addClass("active");
        var start_date = $(this).data('start_date');
        var end_date = $(this).data('end_date');
        alert("Start : " + start_date + "\nEnd : " + end_date);
        $.get('dashboard-filter/' + start_date + '/' + end_date, function(data) {
            dashboardFilter(data);
        });
    });

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bota2375/app.bougteba.com/resources/views/gain.blade.php ENDPATH**/ ?>