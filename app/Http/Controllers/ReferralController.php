<?php

namespace App\Http\Controllers;

use App\Withdrawal;
use App\WithdrawReferral;
use App\GeneralSetting;
use App\User;
use App\Sale;
use App\Referral;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use DB;

class ReferralController extends Controller
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

            return view('referral.index', compact('all_permission'));
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

            $lims_referral_data = Referral::all();

            $lims_general_setting_data = GeneralSetting::latest()->first();
            $min_withdraw = $lims_general_setting_data->min_withdraw;

            return view('referral.facturation', compact('lims_referral_data', 'all_permission', 'min_withdraw'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function facturationData(Request $request)
    {
        if (!empty($request->input('search_string')))
            $search_string = $request->input('search_string');
        else
            $search_string = "";

        $totalData = Referral::all()
            ->count();

        $totalFiltered = $totalData;
        if ($request->input('length') != -1)
            $limit = $request->input('length');
        else
            $limit = $totalData;
        $start = $request->input('start');

        if (empty($request->input('search_string'))) {
            $referrals = Referral::where('montant', '!=', 0)
                ->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();

            $totalFiltered = Referral::where('montant', '!=', 0)
                ->count();
        } else {
            $search = $request->input('search_string');
            $referrals = Referral::select('referrals.*')
                ->with('user')
                ->join('users', 'users.id', '=', 'referrals.referral_id')
                ->where([
                    ['montant', '!=', 0],
                    ['users.first_name', 'LIKE', "%{$search}%"]
                ])
                ->orwhere([
                    ['montant', '!=', 0],
                    ['users.last_name', 'LIKE', "%{$search}%"]
                ])
                ->offset($start)
                ->limit($limit)
                ->orderBy('referrals.id', 'desc')
                ->get();

            $totalFiltered = Referral::select('referrals.*')
                ->with('user')
                ->join('users', 'users.id', '=', 'referrals.referral_id')
                ->where([
                    ['montant', '!=', 0],
                    ['users.first_name', 'LIKE', "%{$search}%"]
                ])
                ->orwhere([
                    ['montant', '!=', 0],
                    ['users.last_name', 'LIKE', "%{$search}%"]
                ])
                ->count();
        }

        $data = array();
        if (!empty($referrals)) {
            foreach ($referrals as $key => $referral) {
                $lims_vendeur_data = User::where('id', $referral->referral_user_id)->first();
                $lims_referral_data = User::where('id', $referral->referral_id)->first();
                $nestedData['key'] = $key;
                $nestedData['vendeur'] = strtoupper($lims_vendeur_data->last_name) . ' ' . ucfirst($lims_vendeur_data->first_name);
                $nestedData['parrain'] = strtoupper($lims_referral_data->last_name) . ' ' . ucfirst($lims_referral_data->first_name);
                $nestedData['gain'] = number_format($referral->montant, 2, '.', ' ');

                $nestedData['facturation'] = array(
                    '[ 
                    "' . $referral->referral_user_id . '"', //0
                    ' "' . $referral->referral_id . '"', //1
                    ' "' . $referral->montant . '"', //2
                    ' "' . $referral->id . '"]' //3
                );
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function referralData(Request $request)
    {
        if (!empty($request->input('search_string')))
            $search_string = $request->input('search_string');
        else
            $search_string = "";

        if ($request->input('status_id') != 2)
            $status_id = $request->input('status_id');
        else
            $status_id = 2;

        if (Auth::user()->role_id > 2 && config('staff_access') == 'own' && $status_id != 2)
            $totalData = DB::table('withdraw_referrals')
                ->select('facture_no')
                ->where([
                    ['referral_id', Auth::id()],
                    ['status', $status_id]
                ])
                ->groupBy('facture_no')
                ->get();
        elseif (Auth::user()->role_id > 2 && config('staff_access') == 'own')
            $totalData = DB::table('withdraw_referrals')
                ->select('facture_no')
                ->where('referral_id', Auth::id())
                ->groupBy('facture_no')
                ->get();
        elseif ($status_id != 2)
            $totalData = DB::table('withdraw_referrals')
                ->select('facture_no')
                ->where('status', $status_id)
                ->groupBy('facture_no')
                ->get();
        else
            $totalData = DB::table('withdraw_referrals')
                ->select('facture_no')
                ->groupBy('facture_no')
                ->get();

        $totalData = count($totalData);
        if ($request->input('length') != -1)
            $limit = $request->input('length');
        else
            $limit = $totalData;
        $start = $request->input('start');
        if (empty($request->input('search_string'))) {
            if (Auth::user()->role_id > 2 && config('staff_access') == 'own' && $status_id != 2) {
                $factures = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where([
                        ['referral_id', Auth::id()],
                        ['status', $status_id]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->groupBy('facture_no')
                    ->get();

                $totalFiltered = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where([
                        ['referral_id', Auth::id()],
                        ['status', $status_id]
                    ])
                    ->groupBy('facture_no')
                    ->get();
            } elseif (Auth::user()->role_id > 2 && config('staff_access') == 'own') {
                $factures = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where('referral_id', Auth::id())
                    ->offset($start)
                    ->limit($limit)
                    ->groupBy('facture_no')
                    ->get();

                $totalFiltered = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where('referral_id', Auth::id())
                    ->groupBy('facture_no')
                    ->get();
            } elseif ($status_id != 2) {
                $factures = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where('status', $status_id)
                    ->offset($start)
                    ->limit($limit)
                    ->groupBy('facture_no')
                    ->get();

                $totalFiltered = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where('status', $status_id)
                    ->groupBy('facture_no')
                    ->get();
            } else {
                $factures = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->offset($start)
                    ->limit($limit)
                    ->groupBy('facture_no')
                    ->get();

                $totalFiltered = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->groupBy('facture_no')
                    ->get();
            }
        } else {
            $search = $request->input('search_string');
            if (Auth::user()->role_id > 2 && config('staff_access') == 'own' && $status_id != 2) {
                $factures = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where([
                        ['referral_id', Auth::id()],
                        ['status', $status_id],
                        ['facture_no', 'LIKE', "%{$search}%"]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->groupBy('facture_no')
                    ->get();

                $totalFiltered = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where([
                        ['referral_id', Auth::id()],
                        ['status', $status_id],
                        ['facture_no', 'LIKE', "%{$search}%"]
                    ])
                    ->groupBy('facture_no')
                    ->get();
            } elseif (Auth::user()->role_id > 2 && config('staff_access') == 'own') {
                $factures = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where([
                        ['referral_id', Auth::id()],
                        ['facture_no', 'LIKE', "%{$search}%"]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->groupBy('facture_no')
                    ->get();

                $totalFiltered = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where([
                        ['referral_id', Auth::id()],
                        ['facture_no', 'LIKE', "%{$search}%"]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->groupBy('facture_no')
                    ->get();
            } elseif ($status_id != 2) {
                $factures = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where([
                        ['status', $status_id],
                        ['facture_no', 'LIKE', "%{$search}%"]
                    ])
                    ->offset($start)
                    ->limit($limit)
                    ->groupBy('facture_no')
                    ->get();

                $totalFiltered = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where([
                        ['status', $status_id],
                        ['facture_no', 'LIKE', "%{$search}%"]
                    ])
                    ->groupBy('facture_no')
                    ->get();
            } else {
                $factures = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where('facture_no', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->groupBy('facture_no')
                    ->get();

                $totalFiltered = DB::table('withdraw_referrals')
                    ->select('facture_no')
                    ->where('facture_no', 'LIKE', "%{$search}%")
                    ->groupBy('facture_no')
                    ->get();
            }
        }

        $data = array();

        if (!empty($factures)) {
            $totalFiltered = count($totalFiltered);
            foreach ($factures as $key => $facture) {
                $nestedData['key'] = $key;
                $nestedData['facture_no'] = $facture->facture_no;

                $lims_facture_data = WithdrawReferral::where('facture_no', $facture->facture_no)->get();
                $nestedData['withdraw_total'] = count($lims_facture_data);
                $nestedData['withdraw_v_users'] = '<ul style="margin-bottom: 0">';
                $gain = 0;

                foreach ($lims_facture_data as $key => $w_facture) {
                    $lims_user_data = User::where('id', $w_facture->referral_user_id)->first();
                    $nestedData['withdraw_v_users'] .= '<li>' . strtoupper($lims_user_data->last_name) . ' ' . ucfirst($lims_user_data->first_name) . '</li>';

                    $gain += $w_facture->withdraw_amount;
                }

                $nestedData['withdraw_v_users'] .= '</ul>';

                $lims_facture_data = WithdrawReferral::where('facture_no', $facture->facture_no)->first();
                $nestedData['date'] = date(config('date_format'), strtotime($lims_facture_data->created_at->toDateString()));
                if ($lims_facture_data->status == 1) {
                    $nestedData['f_status'] = '<div class="badge badge-success">' . trans('file.Payée') . '</div>';
                } else {
                    $nestedData['f_status'] = '<div class="badge badge-warning">' . trans('file.En virement') . '</div>';
                }

                $lims_user_data = User::where('id', $lims_facture_data->referral_id)->first();
                $nestedData['p_name'] = strtoupper($lims_user_data->last_name) . ' ' . ucfirst($lims_user_data->first_name);
                $nestedData['montant_total'] = number_format($gain, 2, '.', ' ');
                $nestedData['options'] = '<a target="_blank" href="' . route('referral.invoice', $facture->facture_no) . '" class="btn btn-link">
                <i class="fa fa-print"></i> ' . trans('file.Generate Invoice') . '</a>';

                $nestedData['referral'] = array(
                    '[ 
                    "' . date(config('date_format'), strtotime($lims_facture_data->created_at->toDateString())) . '"', //0
                    '"' . $lims_facture_data->id . '"', //1
                    '"' . $lims_facture_data->facture_no . '"]'
                );

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function genInvoice($id)
    {
        $lims_referral_data = WithdrawReferral::find($id);

        return view('referral.invoice', compact('lims_referral_data'));
    }

    public function payBySelection(Request $request)
    {
        // dd($request);
        $referral_id = $request['referralIdArray'];
        $no_facture = 'pfc-' . date("dmy") . '-' . date("His");
        $lims_general_setting_data = GeneralSetting::latest()->first();
        $min_withdraw = $lims_general_setting_data->min_withdraw;

        foreach ($referral_id as $id) {
            $lims_referral_data = Referral::find($id);
            if (!empty($lims_referral_data)) {
                //if ($lims_referral_data->montant >= $min_withdraw) {
                    $facture_no = new WithdrawReferral();
                    $facture_no->referral_user_id = $lims_referral_data->referral_user_id;
                    $facture_no->referral_id = $lims_referral_data->referral_id;
                    $facture_no->withdraw_amount = $lims_referral_data->montant;
                    $facture_no->facture_no = $no_facture;
                    $facture_no->save();
                    
                    $lims_referral_data->paid_amount = $facture_no->withdraw_amount;
                    $lims_referral_data->montant -= $facture_no->withdraw_amount;
                    $lims_referral_data->save();
                //}
            }
        }
        $message = 'Paiement done successfuly !';
        return redirect()->back()->with('message', $message);
    }

    public function paidBySelection(Request $request)
    {
        if (Auth::user()->role_id == 1) {
            $referral_id = $request['referralIdArray'];
            foreach ($referral_id as $facture_no) {
                $lims_withdrawal_ref_data = WithdrawReferral::where('facture_no', $facture_no)->get();
                foreach ($lims_withdrawal_ref_data as $w_referral) {
                    $w_referral->status = 1;
                    $w_referral->save();
                }
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
            $referral_id = $request['referralIdArray'];
            foreach ($referral_id as $facture_no) {
                $lims_withdrawal_ref_data = WithdrawReferral::where('facture_no', $facture_no)->get();
                foreach ($lims_withdrawal_ref_data as $w_referral) {
                    $w_referral->status = 0;
                    $w_referral->save();
                }
            }

            $message = 'En virement done successfuly !';
        } else {
            $message = "Vous n'\êtes pas autorisé !";
        }

        return redirect()->back()->with('message', $message);
    }
    
    public function calcul(Request $request)
    {
        $lims_referral_data = Referral::all();
        $lims_general_setting_data = GeneralSetting::latest()->first();
        $referral_multiplier = $lims_general_setting_data->referral;
        foreach($lims_referral_data as $referral_user) {
            $total_referral = 0;
            $lims_sale_data = Sale::where([
                                            ['user_id', $referral_user->referral_user_id],
                                            ['delivery_status', 4]
                                        ])->get();
            foreach($lims_sale_data as $sale_data) {
                $sale = Sale::find($sale_data->id);
                $sale->paid_referral = $referral_multiplier;
                $sale->save();
                $total_referral += $referral_multiplier;
            }
            $referral = Referral::where('referral_user_id', $referral_user->referral_user_id)->first();
            $referral->montant = $total_referral;
            $referral->paid_amount = 0;
            $referral->save();
        }
        $message = 'Calcul done !';
        return redirect()->back()->with('message', $message);
    }
}