<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Product_Sale;
use App\Gain;

class DevClearCacheController extends Controller
{
    public function clear_views()
    {
        \Artisan::call('view:clear');
        return redirect()->back()->with('message', 'Views Cleared!');
    }

    public function clear_cache()
    {
        \Artisan::call('cache:clear');
        return redirect()->back()->with('message', 'Cache Cleared!');
    }

    public function gain_process()
    {
        $lims_sale_data = Sale::all();
        foreach ($lims_sale_data as $sale) {
            $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();
            $total_original_price = 0;
            $total_qty = 0;
            foreach ($lims_product_sale_data as $product_sale) {
                $total_original_price += $product_sale->original_price * $product_sale->qty;
                $total_qty += $product_sale->qty;
            }
            $data['total_original_price'] = $total_original_price;
            $data['total_qty'] = $total_qty;
            $data['total_livraison'] = $sale->livraison;
            $data['total_discount'] = $sale->total_discount;
            $data['grand_total'] = $sale->grand_total;
            $data['user_id'] = $sale->user_id;
            $data['sale_id'] = $sale->id;

            $lims_gain_data = Gain::where('sale_id', $sale->id)->first();

            if (empty($lims_gain_data)) {
                Gain::create($data);
            }
        }

        $message = "Gain Table created !";

        return redirect('/')->with('message', $message);
    }

// you can also add methods for queue:start, queue:restart etc.
}