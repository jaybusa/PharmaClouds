<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    function index(){
        $user = auth()->user();
        return view('admin.profile.edit',compact('user'));
    }

    function updateProfile(Request $request , User $user) {
        $user->first_name = $request->name;
        $user->email = $request->email;
        $user->role_id = 1;
        $user->name = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('admin.dashboard');

    }
}
