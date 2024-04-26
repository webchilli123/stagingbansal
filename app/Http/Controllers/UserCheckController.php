<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\SMSService;

class UserCheckController extends Controller
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
            return abort('404');
        }

        $request->validate(['username' => 'required']);
            
        $user = User::where('username', $request->username)->first();

        if(isset($user)){

            // return (new SMSService)->sendSMS($user->username)->successful()
            //     ? $user
            //     : ['message' => 'something went wrong with sms'];
            return $user;

        }else{
            return ['message' => 'no such user exists'];
        }
 
    }
}
