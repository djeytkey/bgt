<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Sale;
use App\City;
use App\Product_Sale;
use App\Product;
use App\ProductVariant;
use App\Delivery;
use App\DeliveryStatus;
use App\GeneralSetting;
use App\Referral;
use Spatie\Permission\Models\Role;
use DB;
use Auth;
use App\Warehouse;
use App\PosSetting;
use App\Account;
use App\GiftCard;
use App\Variant;
use Illuminate\Support\Facades\Http;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if (empty($all_permission))
                $all_permission[] = 'dummy text';

            if ($request->input('warehouse_id'))
                $warehouse_id = $request->input('warehouse_id');
            else
                $warehouse_id = 0;

            if ($request->input('status_id'))
                $status_id = $request->input('status_id');
            else
                $status_id = 1;

            if ($request->input('starting_date')) {
                $starting_date = $request->input('starting_date');
                $ending_date = $request->input('ending_date');
            } else {
                $starting_date = "2020-01-01";
                $ending_date = date("Y-m-d");
            }

            $lims_gift_card_list = GiftCard::where("is_active", true)->get();
            $lims_pos_setting_data = PosSetting::latest()->first();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_account_list = Account::where('is_active', true)->get();

            if (Auth::user()->role_id < 2) {
                $all_permission[] = 'isAdmin';
            }

            return view('delivery.index', compact('starting_date', 'ending_date', 'warehouse_id', 'status_id', 'lims_gift_card_list', 'lims_pos_setting_data', 'lims_account_list', 'lims_warehouse_list', 'all_permission'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function deliveryData(Request $request)
    {
        if ($request->input('status_id') != 0)
            $status_id = $request->input('status_id');
        else
            $status_id = 0;

        if (!empty($request->input('staring_date')))
            $staring_date
                = $request->input('staring_date');
        else
            $staring_date = "";

        if (!empty($request->input('ending_date')))
            $ending_date = $request->input('ending_date');
        else
            $ending_date = "";

        if (!empty($request->input('search_string')))
            $search_string = $request->input('search_string');
        else
            $search_string = "";

        if (Auth::user()->role_id > 2 && config('staff_access') == 'own') {
            if ($status_id != 0) {
                $totalData = Sale::where([
                    ['user_id', Auth::id()],
                    ['delivery_status', $status_id]
                ])
                    ->whereNotNull('delivery_status')
                    ->whereDate('created_at', '>=', $request->input('starting_date'))
                    ->whereDate('created_at', '<=', $request->input('ending_date'))
                    ->count();
            } else {
                $totalData = Sale::where('user_id', Auth::id())
                    ->whereNotNull('delivery_status')
                    ->whereDate('created_at', '>=', $request->input('starting_date'))
                    ->whereDate('created_at', '<=', $request->input('ending_date'))
                    ->count();
            }
        } else {
            if ($status_id != 0) {
                $totalData = Sale::where('delivery_status', $status_id)
                    ->whereNotNull('delivery_status')
                    ->whereDate('created_at', '>=', $request->input('starting_date'))
                    ->whereDate('created_at', '<=', $request->input('ending_date'))
                    ->count();
            } else {
                $totalData = Sale::whereNotNull('delivery_status')
                    ->whereDate('created_at', '>=', $request->input('starting_date'))
                    ->whereDate('created_at', '<=', $request->input('ending_date'))
                    ->count();
            }
        }

        $totalFiltered = $totalData;
        if ($request->input('length') != -1)
            $limit = $request->input('length');
        else
            $limit = $totalData;
        $start = $request->input('start');
        $condition = "vide";

        if (empty($request->input('search_string'))) {
            if (Auth::user()->role_id > 2 && config('staff_access') == 'own') {
                if ($status_id != 0) {
                    $sales = Sale::where([
                        ['user_id', Auth::id()],
                        ['delivery_status', $status_id]
                    ])
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('id', 'desc')
                        ->get();

                    $totalFiltered = Sale::where([
                        ['user_id', Auth::id()],
                        ['delivery_status', $status_id]
                    ])
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->count();
                } else {
                    $sales = Sale::where('user_id', Auth::id())
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('id', 'desc')
                        ->get();

                    $totalFiltered = Sale::where('user_id', Auth::id())
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->count();
                }
            } else {
                if ($status_id != 0) {
                    $sales = Sale::where('delivery_status', $status_id)
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('id', 'desc')
                        ->get();

                    $totalFiltered = Sale::where('delivery_status', $status_id)
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->count();
                } else {
                    $sales = Sale::whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('id', 'desc')
                        ->get();

                    $totalFiltered = Sale::whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->count();
                }
            }
        } else {
            $search = $request->input('search_string');
            if (Auth::user()->role_id > 2 && config('staff_access') == 'own') {
                if ($status_id != 0) {
                    $sales = Sale::where([
                        ['user_id', Auth::id()],
                        ['delivery_status', $status_id],
                        ['reference_no', 'LIKE', "%{$search}%"]
                    ])
                        ->orwhere([
                            ['user_id', Auth::id()],
                            ['delivery_status', $status_id],
                            ['customer_name', 'LIKE', "%{$search}%"]
                        ])
                        ->orwhere([
                            ['user_id', Auth::id()],
                            ['delivery_status', $status_id],
                            ['customer_tel', 'LIKE', "%{$search}%"]
                        ])
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('id', 'desc')
                        ->get();

                    $totalFiltered = Sale::where([
                        ['user_id', Auth::id()],
                        ['delivery_status', $status_id],
                        ['reference_no', 'LIKE', "%{$search}%"]
                    ])
                        ->orwhere([
                            ['user_id', Auth::id()],
                            ['delivery_status', $status_id],
                            ['customer_name', 'LIKE', "%{$search}%"]
                        ])
                        ->orwhere([
                            ['user_id', Auth::id()],
                            ['delivery_status', $status_id],
                            ['customer_tel', 'LIKE', "%{$search}%"]
                        ])
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->count();
                } else {
                    $sales = Sale::where([
                        ['user_id', Auth::id()],
                        ['reference_no', 'LIKE', "%{$search}%"]
                    ])
                        ->orwhere([
                            ['user_id', Auth::id()],
                            ['customer_name', 'LIKE', "%{$search}%"]
                        ])
                        ->orwhere([
                            ['user_id', Auth::id()],
                            ['delivery_status', $status_id],
                            ['customer_tel', 'LIKE', "%{$search}%"]
                        ])
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('id', 'desc')
                        ->get();

                    $totalFiltered = Sale::where([
                        ['user_id', Auth::id()],
                        ['reference_no', 'LIKE', "%{$search}%"]
                    ])
                        ->orwhere([
                            ['user_id', Auth::id()],
                            ['customer_name', 'LIKE', "%{$search}%"]
                        ])
                        ->orwhere([
                            ['user_id', Auth::id()],
                            ['delivery_status', $status_id],
                            ['customer_tel', 'LIKE', "%{$search}%"]
                        ])
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->count();
                }
            } else {
                if ($status_id != 0) {
                    $sales = Sale::where([
                        ['delivery_status', $status_id],
                        ['reference_no', 'LIKE', "%{$search}%"]
                    ])
                        ->orwhere([
                            ['delivery_status', $status_id],
                            ['customer_name', 'LIKE', "%{$search}%"]
                        ])
                        ->orwhere([
                            ['delivery_status', $status_id],
                            ['customer_tel', 'LIKE', "%{$search}%"]
                        ])
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('id', 'desc')
                        ->get();

                    $totalFiltered = Sale::where([
                        ['delivery_status', $status_id],
                        ['reference_no', 'LIKE', "%{$search}%"]
                    ])
                        ->orwhere([
                            ['delivery_status', $status_id],
                            ['customer_name', 'LIKE', "%{$search}%"]
                        ])
                        ->orwhere([
                            ['delivery_status', $status_id],
                            ['customer_tel', 'LIKE', "%{$search}%"]
                        ])
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->count();
                } else {
                    $sales = Sale::where('reference_no', 'LIKE', "%{$search}%")
                        ->orwhere('customer_name', 'LIKE', "%{$search}%")
                        ->orwhere('customer_tel', 'LIKE', "%{$search}%")
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('id', 'desc')
                        ->get();

                    $totalFiltered = Sale::where('reference_no', 'LIKE', "%{$search}%")
                        ->orwhere('customer_name', 'LIKE', "%{$search}%")
                        ->orwhere('customer_tel', 'LIKE', "%{$search}%")
                        ->whereNotNull('delivery_status')
                        ->whereDate('created_at', '>=', $request->input('starting_date'))
                        ->whereDate('created_at', '<=', $request->input('ending_date'))
                        ->count();
                }
            }
        }

        $data = array();

        if (!empty($sales)) {
            foreach ($sales as $key => $sale) {
                $lims_delivery_data = Delivery::where('sale_id', $sale->id)->first();

                if ($lims_delivery_data) {
                    $nestedData['key'] = $key;
                    $nestedData['sale_reference_no'] = $sale->reference_no;
                    $nestedData['customer_name'] = $sale->customer_name;
                    $nestedData['customer_phone'] = $sale->customer_tel;
                    $nestedData['customer_address'] = preg_replace('/[\n\r]/', " ", $sale->customer_address);
                    $nestedData['notes'] = preg_replace('/[\n\r]/', " ", $sale->sale_note);

                    $lims_city_data = City::where('id', $sale->customer_city)->first();
                    $nestedData['customer_city'] = $lims_city_data->name;

                    $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();
                    $product_qty = "";
                    $variant_name = "";
                    $product_name = "";

                    if (!empty($lims_product_sale_data)) {
                        $nestedData['products'] = '<ul class="table-products">';
                        foreach ($lims_product_sale_data as $key => $product_sale_data) {
                            $lims_product_data = Product::find($product_sale_data->product_id);
                            $product_name = $lims_product_data->name;
                            $product_qty = $product_sale_data->qty;
                            if ($product_sale_data->variant_id) {
                                $variant_data = Variant::select('name')->find($product_sale_data->variant_id);
                                $variant_name = $variant_data->name;
                            } else
                                $variant_name = "";

                            $nestedData['products'] .= '<li class="single-table-product">' . $product_name . '&nbsp;(&nbsp;' . str_pad($product_qty, 2, '0', STR_PAD_LEFT) . '&nbsp;/&nbsp;' . $variant_name . '&nbsp;)</li>';
                        }
                        $nestedData['products'] .= '</ul>';
                    } else {
                        $nestedData['products'] = "--";
                    }

                    $nestedData['grand_total'] = number_format($sale->grand_total, 2, '.', ' ');

                    if ($sale->facture == 1) {
                        $nestedData['facture'] = '<div class="badge badge-success">' . trans('file.Facturée') . '</div>';
                    } elseif ($sale->facture == 2) {
                        $nestedData['facture'] = '<div class="badge badge-info">' . trans('file.Validée') . '</div>';
                    } else {
                        $nestedData['facture'] = '<div class="badge badge-danger">' . trans('file.Non Payée') . '</div>';
                    }

                    $closed_statuses = array(4, 10, 11, 12);
                    if (!in_array($sale->delivery_status, $closed_statuses) && (Auth::user()->role_id == 1)) {
                        $nestedData['delivery_api'] = '<a class="btn btn-primary send-api" href="' . route("delivery.api", $sale->reference_no) . '" class="btn btn-link"><i class="dripicons-cloud-download"></i> API</a>';
                    } else {
                        $nestedData['delivery_api'] = '---';
                    }

                    $nestedData['options'] = '<div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle ' . Auth::user()->role_id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . trans("file.action") . '
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                    if (Auth::user()->role_id == 1) {
                           $nestedData['options'] .= '
                                   <li>
                                       <a href="' . route("sales.edit", $sale->id) . '" class="btn btn-link"><i class="dripicons-document-edit"></i> ' . trans("file.edit") . '</a>
                                    </li>';
                        // if (!in_array($sale->delivery_status, $closed_statuses)) {
                        //    $nestedData['options'] .= '
                        //            <li>
                        //                <button type="button" class="add-delivery btn btn-link" data-id = "' . $sale->id . '"><i class="fa fa-truck"></i> ' . trans('file.Add Delivery') . '</button>
                        //            </li>
                        //            <li>
                        //                <a href="' . route("sales.edit", $sale->id) . '" class="btn btn-link"><i class="dripicons-document-edit"></i> ' . trans("file.edit") . '</a>
                        //            </li>';
                        // } else {
                        //     $nestedData['options'] .= '
                        //             <li>
                        //                 <a href="' . route("sales.edit", $sale->id) . '" class="btn btn-link"><i class="dripicons-document-edit"></i> ' . trans("file.edit") . '</a>
                        //             </li>';
                        // }
                    }

                    $nestedData['options'] .= '
                                    <li>
                                        <button type="button" class="btn btn-link view"><i class="fa fa-eye"></i> ' . trans('file.View') . '</button>
                                    </li>                
                                </ul>
                            </div>';

                    $nestedData['delivery_reference_no'] = $lims_delivery_data->reference_no;
                    $lims_delivery_status_data = DeliveryStatus::where('reference_no', $lims_delivery_data->reference_no)->orderBy('id', 'desc')->first();
                    switch ($lims_delivery_status_data->status) {
                        case "1":
                            $nestedData['delivery_status'] = '<div class="badge badge-warning">Ramassé<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "2":
                            $nestedData['delivery_status'] = '<div class="badge badge-info">Expédié<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "3":
                            $nestedData['delivery_status'] = '<div class="badge badge-primary">Mise en distribution<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "4":
                            $nestedData['delivery_status'] = '<div class="badge badge-success">Livré<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "5":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Pas de réponse + SMS<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "6":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Injoignable<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "7":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Erreur numéro<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "8":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Reporté<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "9":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Programmé<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "10":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Annulé<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "11":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Refusé<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "12":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Retourné<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "13":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Annulé ( SUIVI )<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "14":
                            $nestedData['delivery_status'] = '<div class="badge badge-info">client intéressé<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "15":
                            $nestedData['delivery_status'] = '<div class="badge badge-info">En cours<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "16":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Pas de reponse ( SUIVI )<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "17":
                            $nestedData['delivery_status'] = '<div class="badge badge-warning">En Voyage<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "18":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Hors-zone<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "19":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Reporté ( SUIVI )<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "20":
                            $nestedData['delivery_status'] = '<div class="badge badge-info">Reçu<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "21":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">En retour par AMANA<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "22":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Reporté aujourd\'hui<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "23":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Expédier par AMANA<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "24":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Injoignable ( SUIVI )<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "25":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Boite Vocal<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "26":
                            $nestedData['delivery_status'] = '<div class="badge badge-danger">Boite Vocal ( SUIVI )<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "27":
                            $nestedData['delivery_status'] = '<div class="badge badge-primary">Nouveau Colis<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                        case "28":
                            $nestedData['delivery_status'] = '<div class="badge badge-primary">Attente De Ramassage<br>' . $lims_delivery_status_data->status_date . '</div>';
                            break;
                    }
                    $sale_delivery_status = $lims_delivery_status_data->status;
                    $sale_delivery_status_date = $lims_delivery_status_data->status_date;

                    $nestedData['delivery'] = array(
                        '[ 
                        "' . date(config('date_format'), strtotime($lims_delivery_data->created_at->toDateString())) . '"', //0
                        '"' . $lims_delivery_data->reference_no . '"', //1
                        '"' . $sale->reference_no . '"', //2
                        '"' . $lims_delivery_data->id . '"', //3
                        '"' . $sale->customer_name . '"', //4
                        '"' . $sale->customer_tel . '"', //5
                        '"' . preg_replace('/[\n\r]/', " ", $sale->customer_address) . '"', //6
                        '"' . $lims_city_data->name . '"', //7
                        '"' . preg_replace('/[\n\r]/', " ", $lims_delivery_data->note) . '"', //8
                        '"' . $lims_delivery_data->user->name . '"', //9
                        '"' . $lims_delivery_data->delivered_by . '"]' //10
                    );

                    $data[] = $nestedData;
                }
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

    public function create($id)
    {
        $lims_delivery_data = Delivery::where('sale_id', $id)->first();
        $lims_sale_data = Sale::where('id', $id)->first();
        $lims_city_data = City::where('id', $lims_sale_data->customer_city)->first();
        if ($lims_delivery_data) {
            $delivery_data[] = $lims_delivery_data->reference_no;
            $delivery_data[] = $lims_sale_data->reference_no;
            $delivery_data[] = $lims_sale_data->customer_name;
            $delivery_data[] = $lims_sale_data->customer_tel;
            $delivery_data[] = $lims_sale_data->customer_address;
            $delivery_data[] = $lims_city_data->name;
            $delivery_data[] = $lims_delivery_data->delivered_by;
            $delivery_data[] = $lims_delivery_data->note;
            $delivery_data[] = date('d/m/Y H:i:s');
        } else { //Première livraison
            $delivery_data[] = 'dr-' . date("dmy") . '-' . date("His");
            $delivery_data[] = $lims_sale_data->reference_no;
            $delivery_data[] = $lims_sale_data->customer_name;          //Customer Name
            $delivery_data[] = $lims_sale_data->customer_tel;           //Customer Tel
            $delivery_data[] = $lims_sale_data->customer_address;       //Customer Address
            $delivery_data[] = $lims_city_data->name;                   //Customer City
            $delivery_data[] = '';                                      //Delivered By
            $delivery_data[] = preg_replace('/[\n\r]/', " ", $lims_sale_data->sale_note);            //Note 
            $delivery_data[] = date('d/m/Y H:i:s');
        }

        return $delivery_data;
    }

    public function store(Request $request)
    {
        //dd($request);
        $data = $request->all();
        $lims_general_setting_data = GeneralSetting::latest()->first();
        $delivery = Delivery::firstOrNew(['reference_no' => $data['reference_no']]);
        $lims_sale_data = Sale::find($data['sale_id']);
        $lims_sale_data->delivery_status = $data['status'];
        if ($data['status'] == 4) {
            $lims_referral_data = Referral::where('referral_user_id', $lims_sale_data->user_id)->count();
            if ($lims_referral_data) {
                $lims_referral_data = Referral::where('referral_user_id', $lims_sale_data->user_id)->first();
                $lims_sale_data->paid_referral = $lims_general_setting_data->referral;
                $lims_referral_data->montant += $lims_general_setting_data->referral;
                $lims_referral_data->save();
            }
        } else {
            if ($lims_sale_data->paid_referral > 0) {
                $to_deduct = $lims_sale_data->paid_referral;
                $lims_sale_data->paid_referral = 0;
                $lims_referral_data = Referral::where('referral_user_id', $lims_sale_data->user_id)->first();
                $lims_referral_data->montant -= $to_deduct;
                $lims_referral_data->save();
            }
        }
        $lims_sale_data->save();
        if ($delivery->exists) {
            $delivery->delivered_by = $data['delivered_by'];
            $delivery->note = $data['note'];
            $delivery->is_close = $data['is_close'];
            $delivery->returned = $data['returned'];
            $delivery->save();
        } else {
            $delivery->reference_no = $data['reference_no'];
            $delivery->sale_id = $data['sale_id'];
            $delivery->user_id = Auth::id();
            $delivery->sold_by = $lims_sale_data->user_id;
            $delivery->delivered_by = $data['delivered_by'];
            $delivery->note = $data['note'];
            $delivery->is_close = $data['is_close'];
            $delivery->returned = $data['returned'];
            $delivery->save();
        }
        $deliveries = new DeliveryStatus();
        $deliveries->reference_no = $data['reference_no'];
        $deliveries->status = $data['status'];
        $deliveries->status_date = $data['status_date'];
        $deliveries->save();

        $message = 'Delivery created successfully';
        if ($data['to_redirect'] == 'sales') {
            return redirect('sales')->with('message', $message);
        } else {
            return redirect('delivery')->with('message', $message);
        }
    }

    public function productDeliveryData($id)
    {
        $lims_delivery_data = Delivery::find($id);
        //return 'madarchod';
        $lims_product_sale_data = Product_Sale::where('sale_id', $lims_delivery_data->sale->id)->get();

        foreach ($lims_product_sale_data as $key => $product_sale_data) {
            $product = Product::select('name', 'code')->find($product_sale_data->product_id);
            if ($product_sale_data->variant_id) {
                $lims_product_variant_data = ProductVariant::select('item_code')->FindExactProduct($product_sale_data->product_id, $product_sale_data->variant_id)->first();
                $product->code = $lims_product_variant_data->item_code;
            }

            $product_sale[0][$key] = $product->code;
            $product_sale[1][$key] = $product->name;
            $product_sale[2][$key] = $product_sale_data->qty;
        }
        return $product_sale;
    }

    public function DeliveryStatus($id)
    {
        $lims_delivery_data = Delivery::find($id);
        
        $lims_delivery_status_data = DeliveryStatus::where('reference_no', $lims_delivery_data->reference_no)->get();

        foreach ($lims_delivery_status_data as $key => $delivery_status) {
            $delivery_statuses[0][$key] = $delivery_status->status;
            $delivery_statuses[1][$key] = $delivery_status->status_date;
            $delivery_statuses[2][$key] = $delivery_status->comment;
        }
        return $delivery_statuses;
    }

    public function sendMail(Request $request)
    {
        $data = $request->all();
        $lims_delivery_data = Delivery::find($data['delivery_id']);
        $lims_sale_data = Sale::find($lims_delivery_data->sale->id);
        $lims_product_sale_data = Product_Sale::where('sale_id', $lims_delivery_data->sale->id)->get();
        $lims_customer_data = Customer::find($lims_sale_data->customer_id);
        if ($lims_customer_data->email) {
            //collecting male data
            $mail_data['email'] = $lims_customer_data->email;
            $mail_data['date'] = date(config('date_format'), strtotime($lims_delivery_data->created_at->toDateString()));
            $mail_data['delivery_reference_no'] = $lims_delivery_data->reference_no;
            $mail_data['sale_reference_no'] = $lims_sale_data->reference_no;
            $mail_data['status'] = $lims_delivery_data->status;
            $mail_data['customer_name'] = $lims_customer_data->name;
            $mail_data['address'] = $lims_customer_data->address . ', ' . $lims_customer_data->city;
            $mail_data['phone_number'] = $lims_customer_data->phone_number;
            $mail_data['note'] = $lims_delivery_data->note;
            $mail_data['prepared_by'] = $lims_delivery_data->user->name;
            if ($lims_delivery_data->delivered_by)
                $mail_data['delivered_by'] = $lims_delivery_data->delivered_by;
            else
                $mail_data['delivered_by'] = 'N/A';
            if ($lims_delivery_data->recieved_by)
                $mail_data['recieved_by'] = $lims_delivery_data->recieved_by;
            else
                $mail_data['recieved_by'] = 'N/A';
            //return $mail_data;

            foreach ($lims_product_sale_data as $key => $product_sale_data) {
                $lims_product_data = Product::select('code', 'name')->find($product_sale_data->product_id);
                $mail_data['codes'][$key] = $lims_product_data->code;
                $mail_data['name'][$key] = $lims_product_data->name;
                if ($product_sale_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('item_code')->FindExactProduct($product_sale_data->product_id, $product_sale_data->variant_id)->first();
                    $mail_data['codes'][$key] = $lims_product_variant_data->item_code;
                }
                $mail_data['qty'][$key] = $product_sale_data->qty;
            }

            //return $mail_data;

            // try{
            //     Mail::send( 'mail.delivery_challan', $mail_data, function( $message ) use ($mail_data)
            //     {
            //         $message->to( $mail_data['email'] )->subject( 'Delivery Challan' );
            //     });
            //     $message = 'Mail sent successfully';
            // }
            // catch(\Exception $e){
            //     $message = 'Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            // }
        } else
            $message = 'Customer does not have email!';

        return redirect()->back()->with('message', $message);
    }

    public function edit($id)
    {
        $lims_delivery_data = Delivery::find($id);
        $customer_sale = DB::table('sales')->join('customers', 'sales.customer_id', '=', 'customers.id')->where('sales.id', $lims_delivery_data->sale_id)->select('sales.reference_no', 'customers.name')->get();

        $delivery_data[] = $lims_delivery_data->reference_no;
        $delivery_data[] = $customer_sale[0]->reference_no;
        $delivery_data[] = $lims_delivery_data->status;
        $delivery_data[] = $lims_delivery_data->delivered_by;
        $delivery_data[] = $lims_delivery_data->recieved_by;
        $delivery_data[] = $customer_sale[0]->name;
        $delivery_data[] = $lims_delivery_data->address;
        $delivery_data[] = $lims_delivery_data->note;
        return $delivery_data;
    }

    public function update(Request $request)
    {
        $input = $request->except('file');
        //return $input;
        $lims_delivery_data = Delivery::find($input['delivery_id']);
        $document = $request->file;
        if ($document) {
            $ext = pathinfo($document->getClientOriginalName(), PATHINFO_EXTENSION);
            $documentName = $input['reference_no'] . '.' . $ext;
            $document->move('public/documents/delivery', $documentName);
            $input['file'] = $documentName;
        }
        $lims_delivery_data->update($input);
        $lims_sale_data = Sale::find($lims_delivery_data->sale_id);
        $lims_customer_data = Customer::find($lims_sale_data->customer_id);
        $message = 'Delivery updated successfully';
        // if($lims_customer_data->email && $input['status'] != 1){
        //     $mail_data['email'] = $lims_customer_data->email;
        //     $mail_data['customer'] = $lims_customer_data->name;
        //     $mail_data['sale_reference'] = $lims_sale_data->reference_no;
        //     $mail_data['delivery_reference'] = $lims_delivery_data->reference_no;
        //     $mail_data['status'] = $input['status'];
        //     $mail_data['address'] = $input['address'];
        //     $mail_data['delivered_by'] = $input['delivered_by'];
        //     try{
        //         Mail::send( 'mail.delivery_details', $mail_data, function( $message ) use ($mail_data)
        //         {
        //             $message->to( $mail_data['email'] )->subject( 'Delivery Details' );
        //         });
        //     }
        //     catch(\Exception $e){
        //         $message = 'Delivery updated successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
        //     }   
        // }
        return redirect('delivery')->with('message', $message);
    }

    public function deleteBySelection(Request $request)
    {
        $delivery_id = $request['deliveryIdArray'];
        foreach ($delivery_id as $id) {
            $lims_delivery_data = Delivery::find($id);
            $lims_delivery_data->delete();
        }
        return 'Delivery deleted successfully';
    }

    public function delete($id)
    {
        $lims_delivery_data = Delivery::find($id);
        $delivery_reference_no = $lims_delivery_data->reference_no;
        $lims_delivery_data->delete();
        $lims_delivery_status_data = DeliveryStatus::where('reference_no', $delivery_reference_no)->get();
        foreach ($lims_delivery_status_data as $delivery_status) {
            $delivery_status->delete();
        }

        return redirect('delivery')->with('not_permitted', 'Delivery deleted successfully');
    }

    /*========== API ==========*/

    public function SendAPi($sale_id)
    {
        $lims_general_setting_data = GeneralSetting::latest()->first();
        $api_id = $lims_general_setting_data->api_id;
        $api_key = $lims_general_setting_data->api_key;
        if (($api_id !== null) && ($api_key !== null)) {
            $response = Http::asForm()->post('https://api.ozonexpress.ma/customers/' . $api_id . '/' . $api_key . '/tracking', [
                'tracking-number' => $sale_id,
            ])->json();
            
            // return "<pre>" . print_r($response, true) . "</pre>";

            if (($response["CHECK_API"]["MESSAGE"] == "Valide API Key") && ($response["TRACKING"]["MESSAGE"] == "Valid tracking number") && (array_key_exists("HISTORY", $response["TRACKING"]))) {
                $lims_sale_data = Sale::where('reference_no', $sale_id)->first();
                $lims_delivery_data = Delivery::where('sale_id', $lims_sale_data->id)->first();

                $lims_delivery_status_data = DeliveryStatus::where('reference_no', $lims_delivery_data->reference_no)->get();
                foreach ($lims_delivery_status_data as $delivery_status) {
                    $delivery_status->delete();
                }

                foreach ($response["TRACKING"]["HISTORY"] as $key => $result) {
                    if ($result["STATUT"] !== null) {
                        $deliveries = new DeliveryStatus();
                        $deliveries->reference_no = $lims_delivery_data->reference_no;
                        $lims_statuses_data = DB::table('statuses')->where('status_name', $result["STATUT"])->first();
                        $deliveries->status = $lims_statuses_data->status_id;
                        $deliveries->status_date = $result["TIME_STR"];
                        $deliveries->comment = $result["COMMENT"];
                        $deliveries->save();
                    }
                }

                $lims_statuses_data = DB::table('statuses')->where('status_name', $response["TRACKING"]["LAST_TRACKING"]["STATUT"])->first();
                $lims_sale_data->delivery_status = $lims_statuses_data->status_id;
                $lims_sale_data->save();

                if ($lims_statuses_data->status_id == 4) {
                    $lims_delivery_data->is_close = 1;
                    $lims_delivery_data->save();
                } else {
                    $lims_delivery_data->is_close = 0;
                    $lims_delivery_data->save();
                }

                $returned_statuses = array(10, 11, 12);

                if (in_array($lims_statuses_data->status_id, $returned_statuses)) {
                    $lims_delivery_data->returned = 1;
                    $lims_delivery_data->save();
                } else {
                    $lims_delivery_data->returned = 0;
                    $lims_delivery_data->save();
                }

                // return "<pre>" . print_r($response["TRACKING"]["LAST_TRACKING"]["STATUT"], true) . "</pre>";
                return redirect()->back()->with('message', 'Delivery API done');
            } else {
                if ($response["CHECK_API"]["MESSAGE"] !== "Valide API Key") {
                    return "Invalid API data";
                    // return "<pre>Invalid API data<br />" . print_r($response, true) . "</pre>";
                } elseif ($response["TRACKING"]["MESSAGE"] !== "Valid tracking number") {
                    return "Invalid Tracking number";
                    // return "<pre>Invalid Tracking number<br />" . print_r($response, true) . "</pre>";
                } elseif (!array_key_exists("HISTORY", $response["TRACKING"])) {
                    return "No statuses found";
                    // return "<pre>No statuses found<br />" . print_r($response, true) . "</pre>";
                }
            }
            return "<pre>" . print_r($response, true) . "</pre>";
        } else {

            return "No API data saved";
        }
    }
}
