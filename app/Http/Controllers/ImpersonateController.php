<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ImpersonateController extends Controller
{
    public function index($id) {
        $user = User::where('id', $id)->first();
        if ($user) {
            session()->put('impersonate', $user->id);
        }

        return redirect('/');
    }

    public function destroy() {
        session()->forget('impersonate');

        return redirect('/user');
    }
}
