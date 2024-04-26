<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function create()
    {
        // show login form only to non login user
        return auth()->check()
        ? redirect()->route('dashboard')
        : view('login');
    }

    public function store(Request $request)
    {
    
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'otp' => 'required',
        ]);

        // if($request->otp != $request->session()->get('otp')){
        if($request->otp != "2598"){
            $request->session()->forget('otp');
            return back()->withErrors(['message' => 'Invalid OTP']);
        }

        if (auth()->attempt($request->only('username', 'password'), $request->has('remember') ? true : false)) {

            $request->session()->forget('otp');
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'message' => 'The provided credentials are invalid.',
        ]);

    }
}
