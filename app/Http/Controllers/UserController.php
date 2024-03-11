<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Roles;
use App\Biller;
use App\Warehouse;
use App\CustomerGroup;
use App\Customer;
use App\Sale;
use App\Referral;
use App\GeneralSetting;
use Auth;
use Hash;
use Keygen;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('users-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            $lims_user_list = User::where('is_deleted', false)->get();
            return view('user.index', compact('lims_user_list', 'all_permission'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('users-add')) {
            $lims_role_list = Roles::where('is_active', true)->get();
            $lims_biller_list = Biller::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            $lims_customer_group_list = CustomerGroup::where('is_active', true)->get();
            return view('user.create', compact('lims_role_list', 'lims_biller_list', 'lims_warehouse_list', 'lims_customer_group_list'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function generatePassword()
    {
        $id = Keygen::numeric(6)->generate();
        return $id;
    }

    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'name' => [
                'max:255',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('is_deleted', false);
                }),
            ],
            'email' => [
                'email',
                'max:255',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('is_deleted', false);
                }),
            ],
        ]);

        if ($request->role_id == 5) {
            $this->validate($request, [
                'phone_number' => [
                    'max:255',
                    Rule::unique('customers')->where(function ($query) {
                        return $query->where('is_active', 1);
                    }),
                ],
            ]);
        }

        $data = $request->all();
        $message = 'User created successfully';
        // try {
        //     Mail::send('mail.user_details', $data, function ($message) use ($data) {
        //         $message->to($data['email'])->subject('User Account Details');
        //     });
        // } catch (\Exception $e) {
        //     $message = 'User created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
        // }
        if (!isset($data['is_active']))
            $data['is_active'] = false;
        if ($data['discount'] == null)
            $data['discount'] = "0";
        $data['is_deleted'] = false;
        $data['password'] = bcrypt($data['password']);
        $data['phone'] = $data['phone_number'];
        $data['name'] = slugify($data['name']);
        User::create($data);
        if ($data['role_id'] == 5) {
            $data['name'] = $data['customer_name'];
            $data['phone_number'] = $data['phone'];
            $data['is_active'] = true;
            Customer::create($data);
        }
        return redirect('user')->with('message1', $message);
    }

    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('users-edit')) {
            $lims_user_data = User::find($id);
            $lims_role_list = Roles::where('is_active', true)->get();
            $lims_biller_list = Biller::where('is_active', true)->get();
            $lims_warehouse_list = Warehouse::where('is_active', true)->get();
            return view('user.edit', compact('lims_user_data', 'lims_role_list', 'lims_biller_list', 'lims_warehouse_list'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        if (!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

        $this->validate($request, [
            'name' => [
                'max:255',
                Rule::unique('users')->ignore($id)->where(function ($query) {
                    return $query->where('is_deleted', false);
                }),
            ],
            'email' => [
                'email',
                'max:255',
                Rule::unique('users')->ignore($id)->where(function ($query) {
                    return $query->where('is_deleted', false);
                }),
            ],
        ]);

        $input = $request->except('password');
        if (!isset($input['is_active']))
            $input['is_active'] = false;
        if (!empty($request['password']))
            $input['password'] = bcrypt($request['password']);
        if (!isset($input['discount']))
            $input['discount'] = "0";
        $lims_user_data = User::find($id);
        $input['name'] = slugify($input['name']);
        $lims_user_data->update($input);
        return redirect('user')->with('message2', 'Data updated successfullly');
    }

    public function profile($id)
    {
        if (Auth::user()->id != $id) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access other user data');
        }
        $lims_user_data = User::find($id);
        return view('user.profile', compact('lims_user_data'));
    }

    public function vip(Request $request)
    {
        $user_id = $request['user_id'];
        $lims_user_data = User::find($user_id);
        if ($lims_user_data->is_vip == "0") {
            $lims_user_data->is_vip = "1";
        } else {
            $lims_user_data->is_vip = "0";
        }

        $lims_user_data->save();

        return redirect('user')->with('message2', 'Data updated successfullly');
    }

    public function profileUpdate(Request $request, $id)
    {
        if (Auth::user()->id != $id) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access other user data');
        }
        if (!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

        $this->validate($request, [
            'name' => [
                'max:255',
                Rule::unique('users')->ignore($id)->where(function ($query) {
                    return $query->where('is_deleted', false);
                }),
            ],
            'email' => [
                'email',
                'max:255',
                Rule::unique('users')->ignore($id)->where(function ($query) {
                    return $query->where('is_deleted', false);
                }),
            ],
        ]);

        $input = $request->all();
        $lims_user_data = User::find($id);
        $lims_user_data->update($input);
        return redirect()->back()->with('message3', 'Data updated successfullly');
    }

    public function changePassword(Request $request, $id)
    {
        if (!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

        $input = $request->all();
        $lims_user_data = User::find($id);
        if ($input['new_pass'] != $input['confirm_pass'])
            return redirect("user/" .  "profile/" . $id)->with('message2', "Please Confirm your new password");

        if (Hash::check($input['current_pass'], $lims_user_data->password)) {
            $lims_user_data->password = bcrypt($input['new_pass']);
            $lims_user_data->save();
        } else {
            return redirect("user/" .  "profile/" . $id)->with('message1', "Current Password doesn't match");
        }
        auth()->logout();
        return redirect('/');
    }

    public function deleteBySelection(Request $request)
    {
        $user_id = $request['userIdArray'];
        foreach ($user_id as $id) {
            $lims_user_data = User::find($id);
            $lims_user_data->is_deleted = true;
            $lims_user_data->is_active = false;
            $lims_user_data->save();
        }
        return 'User deleted successfully!';
    }

    public function destroy($id)
    {
        if (!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

        $lims_user_data = User::find($id);
        $lims_user_data->is_deleted = true;
        $lims_user_data->is_active = false;
        $lims_user_data->save();
        if (Auth::id() == $id) {
            auth()->logout();
            return redirect('/login');
        } else
            return redirect('user')->with('message3', 'Data deleted successfullly');
    }

    public function addReferral(Request $request)
    {
        $data = $request->all();
        $lims_user_data = User::where('id', $data["referral_user_id"])->first();
        if (is_null($lims_user_data->referral)) {
            if ($data["referral_user_id"] == $data["referral_id"]) {
                return redirect()->back()->with('not_permitted', 'L\'utilisateur doit être différent du parrain !');
            } else {
                $lims_users_data = User::where('id', $data["referral_user_id"])->first();
                $lims_users_data->referral = $data["referral_id"];
                $lims_users_data->save();
                $lims_general_setting_data = GeneralSetting::latest()->first();
                $referral_multiply = $lims_general_setting_data->referral;
                $lims_sales_data = Sale::where([
                                                    ['user_id', $data["referral_user_id"]],
                                                    ['delivery_status', 4]
                                                ])
                                        //->whereNotNull('withdrawal_id')
                                        ->count();
                $referral_total_bonus = $referral_multiply * $lims_sales_data;
                $referral = Referral::firstOrNew(['referral_user_id'=>$data['referral_user_id']]);
                $referral->referral_user_id = $data['referral_user_id'];
                $referral->referral_id = $data['referral_id'];
                $referral->montant = $referral_total_bonus;
                $referral->save();
                $message = "Parrain ajouté avec succée !";
                return redirect()->back()->with('message', $message);
            }
        } else {
            $lims_referral_data = User::where('id', $lims_user_data->referral)->first();
            $ref_name = strtoupper($lims_referral_data->last_name) . ' ' . ucfirst($lims_referral_data->first_name);
            $message = 'L\'utilisateur a déjà un parrain : "' .$ref_name . '"';
            return redirect()->back()->with('not_permitted', $message);
        }        
    }
    
    public function slugifyAll() {
        if (Auth::user()->id == 1) {
            $lims_users_data = User::all();
            $counter = 0;
            foreach($lims_users_data as $key => $user) {
                $user->name = slugify($user->name);
                $user->save();
                $counter++;
            }
            $message = "All Done : " . $counter;
            return redirect()->back()->with('message3', $message);
        } else {
            return redirect()->back()->with('not_permitted', "You are not allowed to do this process !");
        }
    }
}
