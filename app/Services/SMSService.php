<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SMSService
{

    public function sendSMS(string $user)
    {
        
        $otp = random_int('100000', '999999');
        
        session(['otp' => $otp]); // store otp in session of checking

        $app = config('app.name');
        $mobile_numbers = config('app.mobile_number_1').','.config('app.mobile_number_2');

        $domain = config('services.smsduniya.domain');
        $username = urlencode(config('services.smsduniya.username'));
        $password = urlencode(config('services.smsduniya.password'));
        $sender = config('services.smsduniya.sender_id');
        $content = "OTP for $app Login is $otp request by user $user Develop By: webchilli consulting"; 
        $message = urlencode($content);
        $route = 'T'; 
        $peid = config('services.smsduniya.entity_id');
        $tempid = config('services.smsduniya.template_id'); 

        $url = "$domain/sendsms?uname=$username&pwd=$password&senderid=$sender&to=$mobile_numbers&msg=$message&route=$route&peid=$peid&tempid=$tempid";

        return Http::post($url);

    }

}