<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {  

        if($request->isMethod('GET')){
            return abort(404);
        }

       auth()->logout();
       $request->session()->invalidate();
       $request->session()->regenerateToken();
       return redirect()->route('login');
    }
}
