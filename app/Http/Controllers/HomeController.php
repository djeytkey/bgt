<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Returns;
use App\ReturnPurchase;
use App\ProductPurchase;
use App\Purchase;
use App\Expense;
use App\Payroll;
use App\Quotation;
use App\Payment;
use App\Account;
use App\Product_Sale;
use App\Customer;
use App\Delivery;
use App\Withdrawal;
use App\WithdrawReferral;
use App\GeneralSetting;
use App\Referral;
use App\Gain;
use DB;
use Lang;
use Auth;
use Printing;
use Rawilk\Printing\Contracts\Printer;

/*use vendor\autoload;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;*/

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('home');
    }

    public function index(Request $request)
    {
        $end_date = date("Y") . '-' . date("m") . '-' . date('t', mktime(0, 0, 0, date("m"), 1, date("Y")));
        $yearly_sale_amount = [];

        $current_filter = 'monthly';

        $general_setting = DB::table('general_settings')->latest()->first();
        $is_referral = Referral::where('referral_id', Auth::id())->count();
        if (Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') { // Role Vendeur
            $start_date = '2020-01-01';
            $total_sales = Sale::where('user_id', Auth::id())->count();
            $livre          = Sale::where([
                                        ['user_id', Auth::id()],
                                        ['delivery_status', 4]
                                    ])
                                    ->count();
            $en_cours       = Sale::where('user_id', Auth::id())
                                    ->whereNotIn('delivery_status', [4, 10, 11, 12])
                                    ->whereNotNull('delivery_status')
                                    ->count();
            $returned       = Sale::where('user_id', Auth::id())
                                    ->whereIn('delivery_status', [10, 12])
                                    ->count();
            $refused        = Sale::where([
                                        ['user_id', Auth::id()],
                                        ['delivery_status', 11]
                                    ])
                                    ->count();
            $new_sale       = Sale::where([
                                        ['user_id', Auth::id()],
                                        ['is_valide', 1]
                                    ])
                                    ->whereNull('delivery_status')
                                    ->count();

            $total_vendeur = 0;
            $total_referral = 0;
            $total_m_referral = 0;
            $total_paid_referral = 0;
            $total_m_referral = Referral::where('referral_id', Auth::id())
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->sum('montant');
            $total_paid_referral = Referral::where('referral_id', Auth::id())
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->sum('paid_amount');
            $total_referral = $total_m_referral + $total_paid_referral;

            $profit = 0;
            $reste = 0;
            $total_withdraws = 0;
            $demande_withdraws = 0;
            $original_price = 0;
            $net_price = 0;
            $livraison = 0;
            $original_price_withdraws = 0;
            $net_price_withdraws = 0;
            $livraison_withdraws = 0;
            $total_discount = 0;
            $total_discount_withdraws = 0;
            $profit_row = 4;
            //$count_row = 3;
            $best_selling_qty = 0;
            $best_saler = 0;

            $lims_sale_data = Sale::where([
                ['user_id', Auth::id()],
                ['delivery_status', 4]
            ])
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->get();

            foreach ($lims_sale_data as $sale) {
                $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();
                foreach ($lims_product_sale_data as $product_sale) {
                    $original_price += $product_sale->original_price * $product_sale->qty;
                }
                $net_price += $sale->grand_total;
                $livraison += $sale->livraison;
                $total_discount += $sale->total_discount;
            }

            $profit = $net_price + $total_discount - $original_price - $livraison;

            $lims_sale_data = Sale::where([
                ['user_id', Auth::id()],
                ['delivery_status', 4]
            ])
                ->whereNotNull('withdrawal_id')
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->get();

            foreach ($lims_sale_data as $sale) {
                $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();
                foreach ($lims_product_sale_data as $product_sale) {
                    $original_price_withdraws += $product_sale->original_price * $product_sale->qty;
                }
                $net_price_withdraws += $sale->grand_total;
                $livraison_withdraws += $sale->livraison;
                $total_discount_withdraws += $sale->total_discount;
            }

            $total_withdraws = $net_price_withdraws + $total_discount_withdraws - $original_price_withdraws - $livraison_withdraws;

            $reste = $profit - $total_withdraws;

            return view('index', compact('is_referral', 'demande_withdraws', 'total_withdraws', 'reste', 'total_sales', 'livre', 'en_cours', 'returned', 'refused', 'new_sale', 'profit', 'profit_row', 'total_referral', 'total_paid_referral', 'total_m_referral', 'current_filter'));
        } else { // Role Admin
            if ($request->input('start_date'))
                $start_date = $request->input('start_date');
            else
                $start_date = date('Y') . '-' . date('m') . '-' . '01';

            if ($request->input('end_date'))
                $end_date = $request->input('end_date');
            else
                $end_date = date("Y") . '-' . date("m") . '-' . date('t', mktime(0, 0, 0, date("m"), 1, date("Y")));
            
            if ($request->input('current_filter'))
                $current_filter = $request->input('current_filter');
            else
                $current_filter = 'monthly';

            $total_sales    = Sale::count();
            $livre          = Sale::where('delivery_status', 4)
                                    ->count();
            $en_cours       = Sale::whereNotIn('delivery_status', [4, 10, 11, 12])
                                    ->whereNotNull('delivery_status')
                                    ->count();
            $returned       = Sale::whereIn('delivery_status', [10, 12])
                                    ->count();
            $refused        = Sale::where('delivery_status', 11)
                                    ->count();
            $new_sale       = Sale::where('is_valide', 1)
                                    ->whereNull('delivery_status')
                                    ->count();

            /** Début gains table **/

            // $lims_sale_data = Sale::all();
            // foreach ($lims_sale_data as $sale) {
            //     $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();
            //     $total_original_price = 0;
            //     $total_qty = 0;
            //     foreach ($lims_product_sale_data as $product_sale) {
            //         $total_original_price += $product_sale->original_price * $product_sale->qty;
            //         $total_qty += $product_sale->qty;
            //     }
            //     $data['total_original_price'] = $total_original_price;
            //     $data['total_qty'] = $total_qty;
            //     $data['total_livraison'] = $sale->livraison;
            //     $data['total_discount'] = $sale->total_discount;
            //     $data['grand_total'] = $sale->grand_total;
            //     $data['user_id'] = $sale->user_id;
            //     $data['sale_id'] = $sale->id;

            //     $lims_gain_data = Gain::where('sale_id', $sale->id)->first();

            //     if(empty($lims_gain_data)) {
            //         Gain::create($data);
            //     }
            // }

            /** Fin gains table **/

            $total_vendeur = 0;
            $total_referral = 0;
            $total_m_referral = 0;
            $total_paid_referral = 0;

            $total_m_referral = Referral::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->sum('montant');

            $total_paid_referral = Referral::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->sum('paid_amount');
            $total_referral = $total_m_referral + $total_paid_referral;

            /***********************************/

            $profit = 0;
            $reste = 0;
            $total_withdraws = 0;
            $demande_withdraws = 0;
            $original_price = 0;
            $net_price = 0;
            $livraison = 0;
            $original_price_withdraws = 0;
            $net_price_withdraws = 0;
            $livraison_withdraws = 0;
            $total_discount = 0;
            $total_discount_withdraws = 0;

            $original_price = 0;
            $livraison = 0;
            $discount = 0;
            $grand_total = 0;
            $original_price_withdraws = 0;
            $livraison_withdraws = 0;
            $discount_withdraws = 0;
            $grand_total_withdraws = 0;

            $profit_row = 3;
            //$count_row = 2;

            /** Début du calcul du profit total des vendeurs **/

            $lims_sale_data = Sale::where('delivery_status', 4)
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->get();

            foreach ($lims_sale_data as $sale) {
                $lims_gain_data = Gain::where('sale_id', $sale->id)->first();
                if ($lims_gain_data) {
                    $original_price += $lims_gain_data->total_original_price;
                    $livraison += $lims_gain_data->total_livraison;
                    $discount += $lims_gain_data->total_discount;
                    $grand_total += $lims_gain_data->grand_total;
                }
            }
            $profit = $grand_total + $discount - $original_price - $livraison;

            /** Fin du calcul du profit total des vendeurs **/

            /** Début du calcul du profit total des vendeurs (facturés) **/

            $lims_sale_data = Sale::where('delivery_status', 4)
                ->whereNotNull('withdrawal_id')
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->get();

            foreach ($lims_sale_data as $sale) {
                $lims_gain_data = Gain::where('sale_id', $sale->id)->first();
                if ($lims_gain_data) {
                    $original_price_withdraws += $lims_gain_data->total_original_price;
                    $livraison_withdraws += $lims_gain_data->total_livraison;
                    $discount_withdraws += $lims_gain_data->total_discount;
                    $grand_total_withdraws += $lims_gain_data->grand_total;
                }
            }
            $total_withdraws = $grand_total_withdraws + $discount_withdraws - $original_price_withdraws - $livraison_withdraws;

            /** Fin du calcul du profit total des vendeurs (facturés) **/

            $reste = $profit - $total_withdraws;

            /***********************************/

            $best_selling_qty = Product_Sale::select(DB::raw('product_id, sum(qty) as sold_qty'))
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->groupBy('product_id')
                ->orderBy('sold_qty', 'desc')
                ->take(10)
                ->get();

            $best_saler       = Gain::select(DB::raw('
                                            user_id, (sum(grand_total) + sum(total_discount) - sum(total_original_price) - sum(total_livraison)) as t_gain '))
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->groupBy('user_id')
                ->orderBy('t_gain', 'desc')
                ->take(10)
                ->get();

            //Sales flow of last 6 months
            $start = strtotime(date('Y-m-01', strtotime('-6 month', strtotime(date('Y-m-d')))));
            $end = strtotime(date('Y-m-' . date('t', mktime(0, 0, 0, date("m"), 1, date("Y")))));

            while ($start < $end) {
                $start_date = date("Y-m", $start) . '-' . '01';
                $end_date = date("Y-m", $start) . '-' . date('t', mktime(0, 0, 0, date("m", $start), 1, date("Y", $start)));

                $sales_month = Sale::where('delivery_status', 4)
                    ->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)
                    ->count();

                $sales_by_month[] = $sales_month;
                $eng_month = date("F", strtotime($start_date));
                $current_lang = Lang::locale();

                switch ($eng_month) {
                    case "January":
                        if ($current_lang == "en") {
                            $month[] = "January";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Janvier";
                        } else {
                            $month[] = "يناير";
                        }
                        break;

                    case "February":
                        if ($current_lang == "en") {
                            $month[] = "February";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Février";
                        } else {
                            $month[] = "فبراير";
                        }
                        break;

                    case "March":
                        if ($current_lang == "en") {
                            $month[] = "March";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Mars";
                        } else {
                            $month[] = "مارس";
                        }
                        break;

                    case "April":
                        if ($current_lang == "en") {
                            $month[] = "April";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Avril";
                        } else {
                            $month[] = "أبريل";
                        }
                        break;

                    case "May":
                        if ($current_lang == "en") {
                            $month[] = "May";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Mai";
                        } else {
                            $month[] = "ماي";
                        }
                        break;

                    case "June":
                        if ($current_lang == "en") {
                            $month[] = "June";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Juin";
                        } else {
                            $month[] = "يونيو";
                        }
                        break;

                    case "July":
                        if ($current_lang == "en") {
                            $month[] = "July";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Juillet";
                        } else {
                            $month[] = "يوليوز";
                        }
                        break;

                    case "August":
                        if ($current_lang == "en") {
                            $month[] = "August";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Août";
                        } else {
                            $month[] = "غشت";
                        }
                        break;

                    case "September":
                        if ($current_lang == "en") {
                            $month[] = "September";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Septembre";
                        } else {
                            $month[] = "سبتمبر";
                        }
                        break;

                    case "October":
                        if ($current_lang == "en") {
                            $month[] = "October";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Octobre";
                        } else {
                            $month[] = "أكتوبر";
                        }
                        break;

                    case "November":
                        if ($current_lang == "en") {
                            $month[] = "November";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Novembre";
                        } else {
                            $month[] = "نوفمبر";
                        }
                        break;

                    case "December":
                        if ($current_lang == "en") {
                            $month[] = "December";
                        } elseif ($current_lang == "fr") {
                            $month[] = "Décembre";
                        } else {
                            $month[] = "ديسمبر";
                        }
                        break;
                }

                $start = strtotime("+1 month", $start);
            }

            return view('index', compact('is_referral', 'demande_withdraws', 'total_withdraws', 'reste', 'total_sales', 'livre', 'en_cours', 'returned', 'refused', 'new_sale', 'profit', 'profit_row', 'total_paid_referral', 'total_m_referral', 'total_referral', 'best_selling_qty', 'best_saler', 'sales_by_month', 'month', 'current_lang', 'current_filter'));
        }
    }

    public function gains()
    {
        $start_date = '2023-11-01';
        $end_date = date("Y") . '-' . date("m") . '-' . date('t', mktime(0, 0, 0, date("m"), 1, date("Y")));
        $yearly_sale_amount = [];

        $general_setting = DB::table('general_settings')->latest()->first();
        $is_referral = Referral::where('referral_id', Auth::id())->count();
        if (Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') { // Role Vendeur
            $total_sales = Sale::where('user_id', Auth::id())->count();
            $livre          = Sale::where([
                ['user_id', Auth::id()],
                ['delivery_status', 4]
            ])
                ->count();
            $en_cours       = Sale::where([
                ['user_id', Auth::id()],
                ['delivery_status', '!=', 4]
            ])
                ->whereBetween('delivery_status', [1, 9])
                ->count();
            $returned       = Sale::where('user_id', Auth::id())
                ->whereBetween('delivery_status', [10, 12])
                ->count();
            $refused        = Sale::where([
                ['user_id', Auth::id()],
                ['delivery_status', 11]
            ])
                ->count();
            $new_sale       = Sale::where([
                ['user_id', Auth::id()],
                ['is_valide', 1]
            ])
                ->whereNull('delivery_status')
                ->count();

            $total_vendeur = 0;
            $total_v_referral = 0;
            $total_w_referral = 0;
            $total_r_referral = 0;
            $total_v_referral = Referral::where('referral_id', Auth::id())
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->sum('montant');

            $total_w_referral = WithdrawReferral::where([
                ['referral_id', Auth::id()],
                ['status', 1]
            ])
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->sum('withdraw_amount');

            $total_r_referral = $total_v_referral - $total_w_referral;

            $profit = 0;
            $reste = 0;
            $total_withdraws = 0;
            $demande_withdraws = 0;
            $original_price = 0;
            $net_price = 0;
            $livraison = 0;
            $original_price_withdraws = 0;
            $net_price_withdraws = 0;
            $livraison_withdraws = 0;
            $total_discount = 0;
            $total_discount_withdraws = 0;
            $profit_row = 4;
            //$count_row = 3;
            $best_selling_qty = 0;
            $best_saler = 0;

            $lims_sale_data = Sale::where([
                ['user_id', Auth::id()],
                ['delivery_status', 4]
            ])
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->get();

            foreach ($lims_sale_data as $sale) {
                $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();
                foreach ($lims_product_sale_data as $product_sale) {
                    $original_price += $product_sale->original_price * $product_sale->qty;
                }
                $net_price += $sale->grand_total;
                $livraison += $sale->livraison;
                $total_discount += $sale->total_discount;
            }

            $profit = $net_price + $total_discount - $original_price - $livraison;

            $lims_sale_data = Sale::where([
                ['user_id', Auth::id()],
                ['delivery_status', 4]
            ])
                ->whereNotNull('withdrawal_id')
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->get();

            foreach ($lims_sale_data as $sale) {
                $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();
                foreach ($lims_product_sale_data as $product_sale) {
                    $original_price_withdraws += $product_sale->original_price * $product_sale->qty;
                }
                $net_price_withdraws += $sale->grand_total;
                $livraison_withdraws += $sale->livraison;
                $total_discount_withdraws += $sale->total_discount;
            }

            $total_withdraws = $net_price_withdraws + $total_discount_withdraws - $original_price_withdraws - $livraison_withdraws;

            $reste = $profit - $total_withdraws;
        } else { // Role Admin
            $total_sales    = Sale::all()->count();
            $livre          = Sale::where('delivery_status', 4)
                ->count();
            $en_cours       = Sale::where('delivery_status', '!=', 4)
                ->whereBetween('delivery_status', [1, 9])
                ->count();
            $returned       = Sale::whereBetween('delivery_status', [10, 12])
                ->count();
            $refused        = Sale::where('delivery_status', 11)
                ->count();
            $new_sale       = Sale::where('is_valide', 1)
                ->whereNull('delivery_status')
                ->count();

            /** Début gains table **/

            // $lims_sale_data = Sale::all();
            // foreach ($lims_sale_data as $sale) {
            //     $lims_product_sale_data = Product_Sale::where('sale_id', $sale->id)->get();
            //     $total_original_price = 0;
            //     $total_qty = 0;
            //     foreach ($lims_product_sale_data as $product_sale) {
            //         $total_original_price += $product_sale->original_price * $product_sale->qty;
            //         $total_qty += $product_sale->qty;
            //     }
            //     $data['total_original_price'] = $total_original_price;
            //     $data['total_qty'] = $total_qty;
            //     $data['total_livraison'] = $sale->livraison;
            //     $data['total_discount'] = $sale->total_discount;
            //     $data['grand_total'] = $sale->grand_total;
            //     $data['user_id'] = $sale->user_id;
            //     $data['sale_id'] = $sale->id;

            //     $lims_gain_data = Gain::where('sale_id', $sale->id)->first();

            //     if(empty($lims_gain_data)) {
            //         Gain::create($data);
            //     }
            // }

            /** Fin gains table **/

            $total_vendeur = 0;
            $total_v_referral = 0;
            $total_w_referral = 0;
            $total_r_referral = 0;
            $total_v_referral = Referral::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->sum('montant');

            $total_w_referral = WithdrawReferral::where('status', 1)
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->sum('withdraw_amount');

            $total_r_referral = $total_v_referral - $total_w_referral;

            /***********************************/

            $profit = 0;
            $reste = 0;
            $total_withdraws = 0;
            $demande_withdraws = 0;
            $original_price = 0;
            $net_price = 0;
            $livraison = 0;
            $original_price_withdraws = 0;
            $net_price_withdraws = 0;
            $livraison_withdraws = 0;
            $total_discount = 0;
            $total_discount_withdraws = 0;

            $original_price = 0;
            $livraison = 0;
            $discount = 0;
            $grand_total = 0;
            $original_price_withdraws = 0;
            $livraison_withdraws = 0;
            $discount_withdraws = 0;
            $grand_total_withdraws = 0;

            $profit_row = 3;
            //$count_row = 2;

            /** Début du calcul du profit total des vendeurs **/

            $lims_sale_data = Sale::where('delivery_status', 4)
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->get();

            foreach ($lims_sale_data as $sale) {
                $lims_gain_data = Gain::where('sale_id', $sale->id)->first();
                if ($lims_gain_data) {
                    $original_price += $lims_gain_data->total_original_price;
                    $livraison += $lims_gain_data->total_livraison;
                    $discount += $lims_gain_data->total_discount;
                    $grand_total += $lims_gain_data->grand_total;
                }
            }
            $profit = $grand_total + $discount - $original_price - $livraison;

            /** Fin du calcul du profit total des vendeurs **/

            /** Début du calcul du profit total des vendeurs (facturés) **/

            $lims_sale_data = Sale::where('delivery_status', 4)
                ->whereNotNull('withdrawal_id')
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->get();

            foreach ($lims_sale_data as $sale) {
                $lims_gain_data = Gain::where('sale_id', $sale->id)->first();
                if ($lims_gain_data) {
                    $original_price_withdraws += $lims_gain_data->total_original_price;
                    $livraison_withdraws += $lims_gain_data->total_livraison;
                    $discount_withdraws += $lims_gain_data->total_discount;
                    $grand_total_withdraws += $lims_gain_data->grand_total;
                }
            }
            $total_withdraws = $grand_total_withdraws + $discount_withdraws - $original_price_withdraws - $livraison_withdraws;

            /** Fin du calcul du profit total des vendeurs (facturés) **/

            $reste = $profit - $total_withdraws;

            /***********************************/

            $best_selling_qty = Product_Sale::select(DB::raw('product_id, sum(qty) as sold_qty'))
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->groupBy('product_id')
                ->orderBy('sold_qty', 'desc')
                ->take(10)
                ->get();

            $best_saler       = Gain::select(DB::raw('
                                            user_id, (sum(grand_total) + sum(total_discount) - sum(total_original_price) - sum(total_livraison)) as t_gain '))
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->groupBy('user_id')
                ->orderBy('t_gain', 'desc')
                ->take(10)
                ->get();
        }

        return view('gain', compact('is_referral', 'demande_withdraws', 'total_withdraws', 'reste', 'total_sales', 'livre', 'en_cours', 'returned', 'refused', 'new_sale', 'profit', 'profit_row', 'total_v_referral', 'total_w_referral', 'total_r_referral', 'best_selling_qty', 'best_saler'));
    }

    public function dashboardFilter(Request $request)
    {
        dd($request);
        $total_sales    = Sale::all()->count();
        $livre          = Sale::where('delivery_status', 4)
            ->count();
        $en_cours       = Sale::where('delivery_status', '!=', 4)
            ->whereBetween('delivery_status', [1, 9])
            ->count();
        $returned       = Sale::whereBetween('delivery_status', [10, 12])
            ->count();
        $refused        = Sale::where('delivery_status', 11)
            ->count();
        $new_sale       = Sale::where('is_valide', 1)
            ->whereNull('delivery_status')
            ->count();

        $total_vendeur = 0;
        $total_v_referral = 0;
        $total_w_referral = 0;
        $total_r_referral = 0;
        $total_v_referral = Referral::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->sum('montant');

        $total_w_referral = WithdrawReferral::where('status', 1)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->sum('withdraw_amount');

        $total_r_referral = $total_v_referral - $total_w_referral;

        /***********************************/

        $profit = 0;
        $reste = 0;
        $total_withdraws = 0;
        $demande_withdraws = 0;
        $original_price = 0;
        $net_price = 0;
        $livraison = 0;
        $original_price_withdraws = 0;
        $net_price_withdraws = 0;
        $livraison_withdraws = 0;
        $total_discount = 0;
        $total_discount_withdraws = 0;

        $original_price = 0;
        $livraison = 0;
        $discount = 0;
        $grand_total = 0;
        $original_price_withdraws = 0;
        $livraison_withdraws = 0;
        $discount_withdraws = 0;
        $grand_total_withdraws = 0;

        $profit_row = 3;
        //$count_row = 2;

        /** Début du calcul du profit total des vendeurs **/

        $lims_sale_data = Sale::where('delivery_status', 4)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();

        foreach ($lims_sale_data as $sale) {
            $lims_gain_data = Gain::where('sale_id', $sale->id)->first();
            if ($lims_gain_data) {
                $original_price += $lims_gain_data->total_original_price;
                $livraison += $lims_gain_data->total_livraison;
                $discount += $lims_gain_data->total_discount;
                $grand_total += $lims_gain_data->grand_total;
            }
        }
        $profit = $grand_total + $discount - $original_price - $livraison;

        /** Fin du calcul du profit total des vendeurs **/

        /** Début du calcul du profit total des vendeurs (facturés) **/

        $lims_sale_data = Sale::where('delivery_status', 4)
            ->whereNotNull('withdrawal_id')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();

        foreach ($lims_sale_data as $sale) {
            $lims_gain_data = Gain::where('sale_id', $sale->id)->first();
            if ($lims_gain_data) {
                $original_price_withdraws += $lims_gain_data->total_original_price;
                $livraison_withdraws += $lims_gain_data->total_livraison;
                $discount_withdraws += $lims_gain_data->total_discount;
                $grand_total_withdraws += $lims_gain_data->grand_total;
            }
        }
        $total_withdraws = $grand_total_withdraws + $discount_withdraws - $original_price_withdraws - $livraison_withdraws;

        /** Fin du calcul du profit total des vendeurs (facturés) **/

        $reste = $profit - $total_withdraws;

        /***********************************/

        $best_selling_qty = Product_Sale::select(DB::raw('product_id, sum(qty) as sold_qty'))
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->groupBy('product_id')
            ->orderBy('sold_qty', 'desc')
            ->take(10)
            ->get();

        $best_saler       = Gain::select(DB::raw('
                                            user_id, (sum(grand_total) + sum(total_discount) - sum(total_original_price) - sum(total_livraison)) as t_gain '))
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->groupBy('user_id')
            ->orderBy('t_gain', 'desc')
            ->take(10)
            ->get();

        //Sales flow of last 6 months
        $start = strtotime(date('Y-m-01', strtotime('-6 month', strtotime(date('Y-m-d')))));
        $end = strtotime(date('Y-m-' . date('t', mktime(0, 0, 0, date("m"), 1, date("Y")))));

        while ($start < $end) {
            $start_date = date("Y-m", $start) . '-' . '01';
            $end_date = date("Y-m", $start) . '-' . date('t', mktime(0, 0, 0, date("m", $start), 1, date("Y", $start)));

            $sales_month = Sale::where('delivery_status', 4)
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->count();

            $sales_by_month[] = $sales_month;
            $eng_month = date("F", strtotime($start_date));
            $current_lang = Lang::locale();

            switch ($eng_month) {
                case "January":
                    if ($current_lang == "en") {
                        $month[] = "January";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Janvier";
                    } else {
                        $month[] = "يناير";
                    }
                    break;

                case "February":
                    if ($current_lang == "en") {
                        $month[] = "February";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Février";
                    } else {
                        $month[] = "فبراير";
                    }
                    break;

                case "March":
                    if ($current_lang == "en") {
                        $month[] = "March";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Mars";
                    } else {
                        $month[] = "مارس";
                    }
                    break;

                case "April":
                    if ($current_lang == "en") {
                        $month[] = "April";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Avril";
                    } else {
                        $month[] = "أبريل";
                    }
                    break;

                case "May":
                    if ($current_lang == "en") {
                        $month[] = "May";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Mai";
                    } else {
                        $month[] = "ماي";
                    }
                    break;

                case "June":
                    if ($current_lang == "en") {
                        $month[] = "June";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Juin";
                    } else {
                        $month[] = "يونيو";
                    }
                    break;

                case "July":
                    if ($current_lang == "en") {
                        $month[] = "July";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Juillet";
                    } else {
                        $month[] = "يوليوز";
                    }
                    break;

                case "August":
                    if ($current_lang == "en") {
                        $month[] = "August";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Août";
                    } else {
                        $month[] = "غشت";
                    }
                    break;

                case "September":
                    if ($current_lang == "en") {
                        $month[] = "September";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Septembre";
                    } else {
                        $month[] = "سبتمبر";
                    }
                    break;

                case "October":
                    if ($current_lang == "en") {
                        $month[] = "October";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Octobre";
                    } else {
                        $month[] = "أكتوبر";
                    }
                    break;

                case "November":
                    if ($current_lang == "en") {
                        $month[] = "November";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Novembre";
                    } else {
                        $month[] = "نوفمبر";
                    }
                    break;

                case "December":
                    if ($current_lang == "en") {
                        $month[] = "December";
                    } elseif ($current_lang == "fr") {
                        $month[] = "Décembre";
                    } else {
                        $month[] = "ديسمبر";
                    }
                    break;
            }

            $start = strtotime("+1 month", $start);
        }

        return view('index', compact('is_referral', 'demande_withdraws', 'total_withdraws', 'reste', 'total_sales', 'livre', 'en_cours', 'returned', 'refused', 'new_sale', 'profit', 'profit_row', 'total_v_referral', 'total_w_referral', 'total_r_referral', 'best_selling_qty', 'best_saler', 'sales_by_month', 'month', 'current_lang', 'current_filter'));
    }
}


/*<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Sale;
use App\Returns;
use App\ReturnPurchase;
use App\ProductPurchase;
use App\Purchase;
use App\Expense;
use App\Payroll;
use App\Quotation;
use App\Payment;
use App\Account;
use App\Product_Sale;
use App\Customer;
use DB;
use Auth;
use Printing;
use Rawilk\Printing\Contracts\Printer;
class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function dashboard()
    {
        return view('home');
    }
    public function index()
    {
        if(Auth::user()->role_id == 5) {
            $customer = Customer::select('id')->where('user_id', Auth::id())->first();
            $lims_sale_data = Sale::with('warehouse')->where('customer_id', $customer->id)->orderBy('created_at', 'desc')->get();
            $lims_payment_data = DB::table('payments')
                           ->join('sales', 'payments.sale_id', '=', 'sales.id')
                           ->where('customer_id', $customer->id)
                           ->select('payments.*', 'sales.reference_no as sale_reference')
                           ->orderBy('payments.created_at', 'desc')
                           ->get();
            $lims_quotation_data = Quotation::with('biller', 'customer', 'supplier', 'user')->orderBy('id', 'desc')->where('customer_id', $customer->id)->orderBy('created_at', 'desc')->get();
            $lims_return_data = Returns::with('warehouse', 'customer', 'biller')->where('customer_id', $customer->id)->orderBy('created_at', 'desc')->get();
            return view('customer_index', compact('lims_sale_data', 'lims_payment_data', 'lims_quotation_data', 'lims_return_data'));
        }
        $start_date = date("Y").'-'.date("m").'-'.'01';
        $end_date = date("Y").'-'.date("m").'-'.date('t', mktime(0, 0, 0, date("m"), 1, date("Y")));
        $yearly_sale_amount = [];
        $general_setting = DB::table('general_settings')->latest()->first();
        if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
            $product_sale_data = Sale::join('product_sales', 'sales.id','=', 'product_sales.sale_id')
                ->select(DB::raw('product_sales.product_id, product_sales.product_batch_id, sum(product_sales.qty) as sold_qty, sum(product_sales.total) as sold_amount'))
                ->where('sales.user_id', Auth::id())
                ->whereDate('product_sales.created_at', '>=' , $start_date)
                ->whereDate('product_sales.created_at', '<=' , $end_date)
                ->groupBy('product_sales.product_id', 'product_sales.product_batch_id')
                ->get();
            $product_revenue = 0;
            $product_cost = 0;
            $profit = 0;
            foreach ($product_sale_data as $key => $product_sale) {
                if($product_sale->product_batch_id)
                    $product_purchase_data = ProductPurchase::where([
                        ['product_id', $product_sale->product_id],
                        ['product_batch_id', $product_sale->product_batch_id]
                    ])->get();
                else
                    $product_purchase_data = ProductPurchase::where('product_id', $product_sale->product_id)->get();
                $purchased_qty = 0;
                $purchased_amount = 0;
                $sold_qty = $product_sale->sold_qty;
                $product_revenue += $product_sale->sold_amount;
                foreach ($product_purchase_data as $key => $product_purchase) {
                    $purchased_qty += $product_purchase->qty;
                    $purchased_amount += $product_purchase->total;
                    if($purchased_qty >= $sold_qty) {
                        $qty_diff = $purchased_qty - $sold_qty;
                        $unit_cost = $product_purchase->total / $product_purchase->qty;
                        $purchased_amount -= ($qty_diff * $unit_cost);
                        break;
                    }
                }
                $product_cost += $purchased_amount;
            }
            $revenue = Sale::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $return = Returns::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $revenue = $revenue - $return;
            $purchase = Purchase::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $profit = $revenue + $purchase_return - $product_cost;
            $expense = Expense::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('amount');
            $recent_sale = Sale::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_purchase = Purchase::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_quotation = Quotation::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_payment = Payment::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
        }
        else {
            $product_sale_data = Product_Sale::select(DB::raw('product_id, product_batch_id, sum(qty) as sold_qty, sum(total) as sold_amount'))->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->groupBy('product_id', 'product_batch_id')->get();
            $product_revenue = 0;
            $product_cost = 0;
            $profit = 0;
            foreach ($product_sale_data as $key => $product_sale) {
                if($product_sale->product_batch_id)
                    $product_purchase_data = ProductPurchase::where([
                        ['product_id', $product_sale->product_id],
                        ['product_batch_id', $product_sale->product_batch_id]
                    ])->get();
                else
                    $product_purchase_data = ProductPurchase::where('product_id', $product_sale->product_id)->get();
                $purchased_qty = 0;
                $purchased_amount = 0;
                $sold_qty = $product_sale->sold_qty;
                $product_revenue += $product_sale->sold_amount;
                foreach ($product_purchase_data as $key => $product_purchase) {
                    $purchased_qty += $product_purchase->qty;
                    $purchased_amount += $product_purchase->total;
                    if($purchased_qty >= $sold_qty) {
                        $qty_diff = $purchased_qty - $sold_qty;
                        $unit_cost = $product_purchase->total / $product_purchase->qty;
                        $purchased_amount -= ($qty_diff * $unit_cost);
                        break;
                    }
                }
                $product_cost += $purchased_amount;
            }
            $revenue = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $return = Returns::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $revenue = $revenue - $return;
            $purchase = Purchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $profit = $revenue + $purchase_return - $product_cost;
            $expense = Expense::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('amount');
            $recent_sale = Sale::orderBy('id', 'desc')->take(5)->get();
            $recent_purchase = Purchase::orderBy('id', 'desc')->take(5)->get();
            $recent_quotation = Quotation::orderBy('id', 'desc')->take(5)->get();
            $recent_payment = Payment::orderBy('id', 'desc')->take(5)->get();
        }
        $best_selling_qty = Product_Sale::select(DB::raw('product_id, sum(qty) as sold_qty'))->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->groupBy('product_id')->orderBy('sold_qty', 'desc')->take(5)->get();
        $yearly_best_selling_qty = Product_Sale::select(DB::raw('product_id, sum(qty) as sold_qty'))->whereDate('created_at', '>=' , date("Y").'-01-01')->whereDate('created_at', '<=' , date("Y").'-12-31')->groupBy('product_id')->orderBy('sold_qty', 'desc')->take(5)->get();
        $yearly_best_selling_price = Product_Sale::select(DB::raw('product_id, sum(total) as total_price'))->whereDate('created_at', '>=' , date("Y").'-01-01')->whereDate('created_at', '<=' , date("Y").'-12-31')->groupBy('product_id')->orderBy('total_price', 'desc')->take(5)->get();
        //cash flow of last 6 months
        $start = strtotime(date('Y-m-01', strtotime('-6 month', strtotime(date('Y-m-d') ))));
        $end = strtotime(date('Y-m-'.date('t', mktime(0, 0, 0, date("m"), 1, date("Y")))));
        while($start < $end)
        {
            $start_date = date("Y-m", $start).'-'.'01';
            $end_date = date("Y-m", $start).'-'.date('t', mktime(0, 0, 0, date("m", $start), 1, date("Y", $start)));
            if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
                $recieved_amount = DB::table('payments')->whereNotNull('sale_id')->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('amount');
                $sent_amount = DB::table('payments')->whereNotNull('purchase_id')->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('amount');
                $return_amount = Returns::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
                $purchase_return_amount = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
                $expense_amount = Expense::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('amount');
                $payroll_amount = Payroll::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('amount');
            }
            else {
                $recieved_amount = DB::table('payments')->whereNotNull('sale_id')->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('amount');
                $sent_amount = DB::table('payments')->whereNotNull('purchase_id')->whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('amount');
                $return_amount = Returns::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
                $purchase_return_amount = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
                $expense_amount = Expense::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('amount');
                $payroll_amount = Payroll::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('amount');
            }
            $sent_amount = $sent_amount + $return_amount + $expense_amount + $payroll_amount;
            
            $payment_recieved[] = number_format((float)($recieved_amount + $purchase_return_amount), 2, '.', ' ');
            $payment_sent[] = number_format((float)$sent_amount, 2, '.', ' ');
            $month[] = date("F", strtotime($start_date));
            $start = strtotime("+1 month", $start);
        }
        // yearly report
        $start = strtotime(date("Y") .'-01-01');
        $end = strtotime(date("Y") .'-12-31');
        while($start < $end)
        {
            $start_date = date("Y").'-'.date('m', $start).'-'.'01';
            $end_date = date("Y").'-'.date('m', $start).'-'.date('t', mktime(0, 0, 0, date("m", $start), 1, date("Y", $start)));
            if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
                $sale_amount = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
                $purchase_amount = Purchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            }
            else{
                $sale_amount = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
                $purchase_amount = Purchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            }
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', ' ');
            $yearly_purchase_amount[] = number_format((float)$purchase_amount, 2, '.', ' ');
            $start = strtotime("+1 month", $start);
        }
        //return $month;
        return view('index', compact('revenue', 'purchase', 'expense', 'return', 'purchase_return', 'profit', 'payment_recieved', 'payment_sent', 'month', 'yearly_sale_amount', 'yearly_purchase_amount', 'recent_sale', 'recent_purchase', 'recent_quotation', 'recent_payment', 'best_selling_qty', 'yearly_best_selling_qty', 'yearly_best_selling_price'));
    }
    public function dashboardFilter($start_date, $end_date)
    {
        $general_setting = DB::table('general_settings')->latest()->first();
        if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
            $revenue = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $return = Returns::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $revenue -= $return;
            $purchase = Purchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $profit = $revenue + $purchase_return - $purchase;
            $data[0] = $revenue;
            $data[1] = $return;
            $data[2] = $profit;
            $data[3] = $purchase_return;
        }
        else{
            $revenue = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $return = Returns::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $revenue -= $return;
            $purchase = Purchase::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $profit = $revenue + $purchase_return - $purchase;
            $data[0] = $revenue;
            $data[1] = $return;
            $data[2] = $profit;
            $data[3] = $purchase_return;
        }
        
        return $data;
    }
    public function myTransaction($year, $month)
    {
        $start = 1;
        $number_of_day = date('t', mktime(0, 0, 0, $month, 1, $year));
        while($start <= $number_of_day)
        {
            if($start < 10)
                $date = $year.'-'.$month.'-0'.$start;
            else
                $date = $year.'-'.$month.'-'.$start;
            $sale_generated[$start] = Sale::whereDate('created_at', $date)->where('user_id', Auth::id())->count();
            $sale_grand_total[$start] = Sale::whereDate('created_at', $date)->where('user_id', Auth::id())->sum('grand_total');
            $purchase_generated[$start] = Purchase::whereDate('created_at', $date)->where('user_id', Auth::id())->count();
            $purchase_grand_total[$start] = Purchase::whereDate('created_at', $date)->where('user_id', Auth::id())->sum('grand_total');
            $quotation_generated[$start] = Quotation::whereDate('created_at', $date)->where('user_id', Auth::id())->count();
            $quotation_grand_total[$start] = Quotation::whereDate('created_at', $date)->where('user_id', Auth::id())->sum('grand_total');
            $start++;
        }
        $start_day = date('w', strtotime($year.'-'.$month.'-01')) + 1;
        $prev_year = date('Y', strtotime('-1 month', strtotime($year.'-'.$month.'-01')));
        $prev_month = date('m', strtotime('-1 month', strtotime($year.'-'.$month.'-01')));
        $next_year = date('Y', strtotime('+1 month', strtotime($year.'-'.$month.'-01')));
        $next_month = date('m', strtotime('+1 month', strtotime($year.'-'.$month.'-01')));
        return view('user.my_transaction', compact('start_day', 'year', 'month', 'number_of_day', 'prev_year', 'prev_month', 'next_year', 'next_month', 'sale_generated', 'sale_grand_total','purchase_generated', 'purchase_grand_total','quotation_generated', 'quotation_grand_total'));
    }
}
*/