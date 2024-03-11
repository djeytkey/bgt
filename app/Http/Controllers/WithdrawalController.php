<?php

namespace App\Http\Controllers;

use App\Withdrawal;
use App\GeneralSetting;
use App\PosSetting;
use App\User;
use App\Gain;
use App\Sale;
use App\Referral;
use App\Product_Sale;
use App\Product;
use App\Variant;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use DB;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('withdraw-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if (empty($all_permission))
                $all_permission[] = 'dummy text';

            return view('withdraw.index', compact('all_permission'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function facturation(Request $request)
    {      
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('withdraw-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if (empty($all_permission))
                $all_permission[] = 'dummy text';

            $lims_sales_data = Sale::whereNotNull('facture')->orderBy('id', 'desc')->get();
            $lims_pos_setting_data = PosSetting::latest()->first();

            return view('withdraw.facturation', compact('lims_sales_data', 'lims_pos_setting_data', 'all_permission'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function facturationData(Request $request)
    {
        if($request->input('status_id') != 0)
            $status_id = $request->input('status_id');
        else
            $status_id = 0;

        if(!empty($request->input('search_string')))
            $search_string = $request->input('search_string');
        else
            $search_string = "";

        $totalData = Sale::where([
                                    ['facture', $status_id],
                                    ['delivery_status', 4]
                                ])
                        ->count();

        $totalFiltered = $totalData;
        if($request->input('length') != -1)
            $limit = $request->input('length');
        else
            $limit = $totalData;
        $start = $request->input('start');
        $condition = "vide";

        if(empty($request->input('search_string'))) {
            $sales = Sale::where([
                                    ['facture', $status_id],
                                    ['delivery_status', 4]
                                ])
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy('id', 'desc')
                                ->get();
                                
            $totalFiltered = Sale::where([
                                    ['facture', $status_id],
                                    ['delivery_status', 4]
                                ])
                                ->count();
        } else {
            $search = $request->input('search_string');
            $sales = Sale::where([
                                    ['facture', $status_id],
                                    ['delivery_status', 4],
                                    ['reference_no', 'LIKE', "%{$search}%"]
                                ])
                                // ->orwhere([
                                //     ['facture', $status_id],
                                //     ['delivery_status', 4],
                                //     ['customer_name', 'LIKE', "%{$search}%"]
                                // ])                                    
                                // ->orwhere([
                                //     ['facture', $status_id],
                                //     ['delivery_status', 4],
                                //     ['customer_tel', 'LIKE', "%{$search}%"]
                                // ])
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy('id', 'desc')
                                ->get();

            $totalFiltered = Sale::where([
                                    ['facture', $status_id],
                                    ['delivery_status', 4],
                                    ['reference_no', 'LIKE', "%{$search}%"]
                                ])
                                // ->orwhere([
                                //     ['facture', $status_id],
                                //     ['delivery_status', 4],
                                //     ['customer_name', 'LIKE', "%{$search}%"]
                                // ])                                    
                                // ->orwhere([
                                //     ['facture', $status_id],
                                //     ['delivery_status', 4],
                                //     ['customer_tel', 'LIKE', "%{$search}%"]
                                // ])
                                ->count();
        }
                
        $data = array();
        if(!empty($sales))
        {
            foreach ($sales as $key=>$sale)
            {
                $nestedData['id'] = $sale->id;
                $nestedData['key'] = $key;
                $nestedData['date'] = date(config('date_format'), strtotime($sale->created_at->toDateString()));
                $nestedData['reference_no'] = $sale->reference_no;
                $nestedData['customer_name'] = $sale->customer_name;
                $nestedData['customer_phone'] = $sale->customer_tel;
                $nestedData['remark'] = preg_replace('/[\n\r]/', " ", $sale->sale_note);

                $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();
                $product_qty = "";
                $variant_name = "";
                $product_name = "";
                $original_price = 0;

                if (!empty($lims_product_sale_data))
                {
                    $nestedData['products'] = '<ul class="table-products">';
                    foreach ($lims_product_sale_data as $key => $product_sale_data) {
                        $original_price += $product_sale_data->original_price * $product_sale_data->qty;
                        $lims_product_data = Product::find($product_sale_data->product_id);
                        $product_name = $lims_product_data->name;
                        $product_qty = $product_sale_data->qty;
                        if($product_sale_data->variant_id) {
                            $variant_data = Variant::select('name')->find($product_sale_data->variant_id);
                            $variant_name = $variant_data->name;
                        }
                        else
                            $variant_name = "";
                        
                        $nestedData['products'] .= '<li class="single-table-product">' . $product_name . '&nbsp;(&nbsp;' . str_pad($product_qty, 2, '0', STR_PAD_LEFT) . '&nbsp;/&nbsp;' . $variant_name . '&nbsp;)</li>';
                    }
                    $nestedData['products'] .= '</ul>';
                } else {
                    $nestedData['products'] = "--";
                }

                $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();                

                $nestedData['original_price'] = number_format($original_price, 2, '.', ' ');
                $nestedData['grand_total'] = number_format($sale->grand_total, 2, '.', ' ');
                $nestedData['remise'] = number_format($sale->total_discount, 2, '.', ' ');
                $nestedData['livraison'] = number_format($sale->livraison, 2, '.', ' ');

                $gain = $sale->grand_total + $sale->total_discount - $original_price - $sale->livraison;

                $nestedData['gain'] = number_format($gain, 2, '.', ' ');                

                $nestedData['facturation'] = array( '[ 
                    "'.date(config('date_format'), strtotime($sale->created_at->toDateString())).'"', //0
                    ' "'.$sale->reference_no.'"', //1
                    ' "'.$sale->customer_name.'"', //2
                    ' "'.$sale->customer_tel.'"', //3
                    ' "'.$sale->user_id.'"', //4
                    ' "'.$sale->id.'"', //5
                    ' "'.$sale->grand_total.'"', //6
                    ' "'.preg_replace('/[\n\r]/', " ", $sale->sale_note).'"', //7
                    ' "'.preg_replace('/[\n\r]/', " ", $sale->staff_note).'"', //8
                    ' "'.$request->input('search_string').'"', //9
                    ' "'.$status_id.'"]' //10
                );
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );

        echo json_encode($json_data);
    }
    
    public function withdrawData(Request $request)
    {
        if(!empty($request->input('search_string')))
            $search_string = $request->input('search_string');
        else
            $search_string = "";

        if($request->input('status_id') != 2)
            $status_id = $request->input('status_id');
        else
            $status_id = 2;

        if(Auth::user()->role_id > 2 && config('staff_access') == 'own' && $status_id != 2)
            $totalData = Withdrawal::select('withdrawals.*')
                                    ->with('sale')
                                    ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                    ->where([
                                        ['sales.user_id', Auth::id()],
                                        ['withdrawals.status', $status_id],
                                    ])
                                    ->distinct()
                                    ->get();
                                    
        elseif(Auth::user()->role_id > 2 && config('staff_access') == 'own')
            $totalData = Withdrawal::select('withdrawals.*')
                                    ->with('sale')
                                    ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                    ->where('sales.user_id', Auth::id())
                                    ->distinct()
                                    ->get();
        elseif($status_id != 2)
            $totalData = Withdrawal::select('withdrawals.*')
                                    ->with('sale')
                                    ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                    ->where('withdrawals.status', $status_id)
                                    ->distinct()
                                    ->get();
        else
            $totalData = Withdrawal::select('withdrawals.*')
                                    ->with('sale')
                                    ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                    ->distinct()
                                    ->get();

        $totalData = count($totalData);
        if($request->input('length') != -1)
            $limit = $request->input('length');
        else
            $limit = $totalData;
        $start = $request->input('start');
        $condition = "vide";
        if(empty($request->input('search_string'))) {
            if(Auth::user()->role_id > 2 && config('staff_access') == 'own' && $status_id != 2) {
                $factures = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where([
                                            ['sales.user_id', Auth::id()],
                                            ['withdrawals.status', $status_id],
                                        ])
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('withdrawals.id', 'desc')
                                        ->distinct()
                                        ->get();
                $totalFiltered = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where([
                                            ['sales.user_id', Auth::id()],
                                            ['withdrawals.status', $status_id],
                                        ])
                                        ->distinct()
                                        ->get();
            } elseif(Auth::user()->role_id > 2 && config('staff_access') == 'own') {
                $factures = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where('sales.user_id', Auth::id())
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('withdrawals.id', 'desc')
                                        ->distinct()
                                        ->get();
                $totalFiltered = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where('sales.user_id', Auth::id())
                                        ->distinct()
                                        ->get();
            } elseif($status_id != 2) {
                $factures = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where('withdrawals.status', $status_id)
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('withdrawals.id', 'desc')
                                        ->distinct()
                                        ->get();
                $totalFiltered = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where('withdrawals.status', $status_id)
                                        ->distinct()
                                        ->get();
            } else {
                $factures = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('withdrawals.id', 'desc')
                                        ->distinct()
                                        ->get();
                $totalFiltered = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->distinct()
                                        ->get();
            }
        }
        else
        {
            $search = $request->input('search_string');
            if(Auth::user()->role_id > 2 && config('staff_access') == 'own' && $status_id != 2)
            {
                $condition = "Auth::user()->role_id > 2 && config('staff_access') == 'own' && status_id != 2";
                $factures = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where([
                                            ['sales.user_id', Auth::id()],
                                            ['withdrawals.status', $status_id],
                                            ['withdrawals.facture_no', 'LIKE', "%{$search}%"],
                                        ])
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('withdrawals.id', 'desc')
                                        ->distinct()
                                        ->get();

                $totalFiltered = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where([
                                            ['sales.user_id', Auth::id()],
                                            ['withdrawals.status', $status_id],
                                            ['withdrawals.facture_no', 'LIKE', "%{$search}%"],
                                        ])
                                        ->distinct()
                                        ->get();
            }                
            elseif(Auth::user()->role_id > 2 && config('staff_access') == 'own')
            {
                $condition = "Auth::user()->role_id > 2 && config('staff_access') == 'own'";
                $factures = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where([
                                            ['sales.user_id', Auth::id()],
                                            ['withdrawals.facture_no', 'LIKE', "%{$search}%"],
                                        ])
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('withdrawals.id', 'desc')
                                        ->distinct()
                                        ->get();

                $totalFiltered = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where([
                                            ['sales.user_id', Auth::id()],
                                            ['withdrawals.facture_no', 'LIKE', "%{$search}%"],
                                        ])
                                        ->distinct()
                                        ->get();
            }                
            elseif($status_id != 2)
            {
                $condition = "status_id != 2";
                $factures = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where([
                                            ['withdrawals.status', $status_id],
                                            ['withdrawals.facture_no', 'LIKE', "%{$search}%"],
                                        ])
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('withdrawals.id', 'desc')
                                        ->distinct()
                                        ->get();

                $totalFiltered = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where([
                                            ['withdrawals.status', $status_id],
                                            ['withdrawals.facture_no', 'LIKE', "%{$search}%"],
                                        ])
                                        ->distinct()
                                        ->get();
            }
            else
            {
                $condition = "else";
                $factures = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where('withdrawals.facture_no', 'LIKE', "%{$search}%")
                                        ->offset($start)
                                        ->limit($limit)
                                        ->orderBy('withdrawals.id', 'desc')
                                        ->distinct()
                                        ->get();

                $totalFiltered = Withdrawal::select('withdrawals.*')
                                        ->with('sale')
                                        ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
                                        ->where('withdrawals.facture_no', 'LIKE', "%{$search}%")
                                        ->distinct()
                                        ->get();
            }
        }

        // if(Auth::user()->role_id > 2 && config('staff_access') == 'own') {
        //     $totalData = Withdrawal::select('withdrawals.*')
        //                                 ->with('sale')
        //                                 ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
        //                                 ->where('sales.user_id', Auth::id())
        //                                 ->distinct()
        //                                 ->get();
        // } else {
        //     $totalData = Withdrawal::select('withdrawals.*')
        //                                 ->with('sale')
        //                                 ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
        //                                 ->distinct()
        //                                 ->get();
        // }            

        // $totalData = count($totalData);
        // if($request->input('length') != -1)
        //     $limit = $request->input('length');
        // else
        //     $limit = $totalData;
        // $start = $request->input('start');
        // $condition = "vide";

        // if($status_id == 2) {
        //     if(Auth::user()->role_id > 2 && config('staff_access') == 'own') {
        //         $factures = Withdrawal::select('withdrawals.*')
        //                                 ->with('sale')
        //                                 ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
        //                                 ->where('sales.user_id', Auth::id())
        //                                 ->offset($start)
        //                                 ->limit($limit)
        //                                 ->orderBy('withdrawals.id', 'desc')
        //                                 ->distinct()
        //                                 ->get();

        //         $totalFiltered = Withdrawal::select('withdrawals.*')
        //                                 ->with('sale')
        //                                 ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
        //                                 ->where('sales.user_id', Auth::id())
        //                                 ->distinct()
        //                                 ->get();
        //     } else {
        //         $factures = Withdrawal::select('withdrawals.*')
        //                                 ->with('sale')
        //                                 ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
        //                                 ->offset($start)
        //                                 ->limit($limit)
        //                                 ->orderBy('withdrawals.id', 'desc')
        //                                 ->distinct()
        //                                 ->get();

        //         $totalFiltered = Withdrawal::select('withdrawals.*')
        //                                 ->with('sale')
        //                                 ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
        //                                 ->distinct()
        //                                 ->get();
        //     }                
        // } 
        // else {
        //     $search = $request->input('status_id');
        //     if(Auth::user()->role_id > 2 && config('staff_access') == 'own') {
        //         $factures = Withdrawal::select('withdrawals.*')
        //                                 ->with('sale')
        //                                 ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
        //                                 ->where([
        //                                             ['sales.user_id', Auth::id()],
        //                                             ['withdrawals.status', 'LIKE', "%{$search}%"],
        //                                         ])
        //                                 ->offset($start)
        //                                 ->limit($limit)
        //                                 ->orderBy('withdrawals.id', 'desc')
        //                                 ->distinct()
        //                                 ->get();

        //         $totalFiltered = Withdrawal::select('withdrawals.*')
        //                                 ->with('sale')
        //                                 ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
        //                                 ->where([
        //                                             ['sales.user_id', Auth::id()],
        //                                             ['withdrawals.status', 'LIKE', "%{$search}%"],
        //                                         ])
        //                                 ->distinct()
        //                                 ->get();
        //     } else {
        //         $factures = Withdrawal::select('withdrawals.*')
        //                                 ->with('sale')
        //                                 ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
        //                                 ->where('withdrawals.status', 'LIKE', "%{$search}%")
        //                                 ->offset($start)
        //                                 ->limit($limit)
        //                                 ->orderBy('withdrawals.id', 'desc')
        //                                 ->distinct()
        //                                 ->get();

        //         $totalFiltered = Withdrawal::select('withdrawals.*')
        //                                 ->with('sale')
        //                                 ->join('sales', 'sales.withdrawal_id', '=', 'withdrawals.id')
        //                                 ->where('withdrawals.status', 'LIKE', "%{$search}%")
        //                                 ->distinct()
        //                                 ->get();
        //     }
        // }
        
        $data = array();

        if(!empty($factures))
        {
            $totalFiltered = count($totalFiltered);
            foreach ($factures as $key=>$facture)
            {
                $nestedData['key'] = $key;
                $nestedData['date'] = date(config('date_format'), strtotime($facture->created_at->toDateString()));
                $nestedData['facture_no'] = $facture->facture_no;

                if ($facture->status == 1){
                    $nestedData['f_status'] = '<div class="badge badge-success">'.trans('file.Payée').'</div>';
                } else {
                    $nestedData['f_status'] = '<div class="badge badge-warning">'.trans('file.En virement').'</div>';
                }

                $lims_sale_data = Sale::where('withdrawal_id', $facture->id)->get();
                $nestedData['sales_total'] = count($lims_sale_data);
                $nestedData['sales_reference_no'] = '<ul style="margin-bottom: 0">';
                $gain = 0;

                foreach ($lims_sale_data as $sale) 
                {
                    $nestedData['sales_reference_no'] .= '<li>' . $sale->id . '    (' . $sale->reference_no . ')</li>';

                    // $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();
                    $original_price = 0;

                    $first_name = $sale->user->first_name;
                    $last_name = $sale->user->last_name;

                    $lims_gain_data = Gain::where('sale_id', $sale->id)->first();

                    $gain += $sale->grand_total + $sale->total_discount - $lims_gain_data->total_original_price - $sale->livraison;

                    // foreach ($lims_product_sale_data as $product_sale_data)
                    // {
                    //     $original_price += $product_sale_data->original_price * $product_sale_data->qty;
                    // }

                    // $gain += $sale->grand_total + $sale->total_discount - $original_price - $sale->livraison;
                }

                $nestedData['sales_reference_no'] .= '</ul>';
                $nestedData['v_name'] = strtoupper($last_name) . ' ' . ucfirst($first_name);
                $nestedData['montant_total'] = number_format($gain, 2, '.', ' ');
                $nestedData['options'] = '<a target="_blank" href="'.route('withdraw.invoice', $facture->id).'" class="btn btn-link">
                <i class="fa fa-print"></i> '.trans('file.Generate Invoice').'</a>';

                $nestedData['withdraw'] = array( '[ 
                    "'.date(config('date_format'), strtotime($facture->created_at->toDateString())).'"', //0
                    '"'.$facture->id.'"', //1
                    '"'.$facture->facture_no.'"]'
                );
                
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );

        echo json_encode($json_data);
    }

    public function genInvoice($id)
    {
        $lims_sale_data = Sale::find($id);

        return view('withdraw.invoice', compact('lims_sale_data'));
    }

    public function facturationBySelection(Request $request)
    {
        $sale_id = $request['saleIdArray'];
        foreach ($sale_id as $id) {
            $lims_sale_data = Sale::find($id);
            if ($lims_sale_data->delivery_status == 4 && is_null($lims_sale_data->withdrawal_id)) {
                $lims_sale_data->facture = 2;
                $lims_sale_data->save();
            }
        }
        $message = 'Sales valid for facturation !';
        return redirect()->back()->with('message', $message);
    }

    public function unfacturationBySelection(Request $request)
    {
        $sale_id = $request['saleIdArray'];
        foreach ($sale_id as $id) {
            $lims_sale_data = Sale::find($id);
            if ($lims_sale_data->delivery_status == 4) {
                $lims_sale_data->facture = 0;
                $lims_sale_data->withdrawal_id = null;
                $lims_sale_data->save();
            }
        }
        $message = 'Unvalidation done successfuly !';
        return redirect()->back()->with('message', $message);
    }

    public function payBySelection(Request $request)
    {
        $sale_id = $request['saleIdArray'];
        $facture_no = new Withdrawal();
        $facture_no->facture_no = 'fc-' . date("dmy") . '-'. date("His");
        $facture_no->save();

        $lims_facture_data = Withdrawal::where('facture_no', $facture_no->facture_no)->first();

        foreach ($sale_id as $id) {
            $lims_sale_data = Sale::find($id);
            if ($lims_sale_data->delivery_status == 4) {
                $lims_sale_data->facture = 1;
                $lims_sale_data->withdrawal_id = $lims_facture_data->id;
                $lims_sale_data->save();
                $lims_user_data = User::find($lims_sale_data->user_id);
                $lims_user_data->demande_r = 0;
                $lims_user_data->date_r = date('d/m/Y');
                $lims_user_data->save();
                $lims_general_setting_data = GeneralSetting::latest()->first();
                $referral_multiply = $lims_general_setting_data->referral;
                $lims_referral_data = Referral::where('referral_user_id', $lims_sale_data->user_id)->first();
                if (!empty($lims_referral_data))
                {
                    $lims_referral_data->montant = $lims_referral_data->montant + $referral_multiply;
                    $lims_referral_data->save();
                }
            }
        }
        $message = 'Paiement done successfuly !';
        return redirect()->back()->with('message', $message);
    }

    public function paidBySelection(Request $request)
    {
        if (Auth::user()->role_id == 1) {
            $sale_id = $request['saleIdArray'];
            foreach ($sale_id as $id) {
                $lims_withdrawal_data = Withdrawal::find($id);
                $lims_withdrawal_data->status = 1;
                $lims_withdrawal_data->save();
                /*$lims_sale_data = Sale::where('withdrawal_id', $id)->first();
                $lims_user_data = User::find($lims_sale_data->user_id);
                $lims_user_data->demande_r = 0;
                $lims_user_data->date_r = date('d/m/Y');
                $lims_user_data->save();*/
            }

            $message = 'Paid done successfuly !';
        } else {
            $message = "Vous n'\êtes pas autorisé !";
        }
        
        return redirect()->back()->with('message', $message);
    }

    public function virementBySelection(Request $request)
    {
        if (Auth::user()->role_id == 1) {
            $sale_id = $request['saleIdArray'];
            foreach ($sale_id as $id) {
                $lims_withdrawal_data = Withdrawal::find($id);
                $lims_withdrawal_data->status = 0;
                $lims_withdrawal_data->save();
                /*$lims_sale_data = Sale::where('withdrawal_id', $id)->first();
                $lims_user_data = User::find($lims_sale_data->user_id);
                $lims_user_data->demande_r = 0;
                $lims_user_data->date_r = date('d/m/Y');
                $lims_user_data->save();*/
            }
            $message = 'En virement done successfuly !';
        } else {
            $message = "Vous n'\êtes pas autorisé !";
        }
        return redirect()->back()->with('message', $message);
    }

    public function demanderRetrait($id)
    {
        $lims_users_data = User::find($id);
        $lims_users_data->demande_r = 1;
        $lims_users_data->date_r = date('d/m/Y');
        $lims_users_data->save();

        $message = "Demande de retrait ajoutée avec succés !";

        return redirect()->back()->with('message', $message);
    }

    public function demandesRetrait(Request $request)
    {      
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('withdraw-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if (empty($all_permission))
                $all_permission[] = 'dummy text';

            $lims_demande_data = User::where('demande_r', 1)->orderBy('created_at', 'asc')->get();

            return view('withdraw.demandes-retrait', compact('lims_demande_data', 'all_permission'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function demandeData(Request $request)
    {
        $totalData = User::where('demande_r', true)->count();
        $totalFiltered = $totalData; 

        if($request->input('length') != -1)
            $limit = $request->input('length');
        else
            $limit = $totalData;
        $dir = $request->input('order.0.dir');
        $demandes = User::where('demande_r', true)
                            ->limit($limit)
                            ->orderBy('date_r', 'asc')
                            ->get();
        
        $data = array();
        if(!empty($demandes))
        {
            foreach ($demandes as $key=>$demande)
            {
                $nestedData['id'] = $demande->id;
                $nestedData['key'] = $key;
                $nestedData['v_name'] = strtoupper($demande->last_name) . ' ' . ucfirst($demande->first_name);
                $nestedData['v_date'] = $demande->date_r;
                $nestedData['options'] = '<a href="'.route('withdraw.facturation', ['search_string' => $demande->name, 'status_id' => 0]).'" class="btn btn-link">
                <i class="fa fa-money"></i> '.trans('file.Facturation').'</a>';

                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
            
        echo json_encode($json_data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //   
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function factureView(Request $request, $id)
    {
        //
    }
}
