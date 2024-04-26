<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\TransferTransaction;

class SaleBillService
{

    public function sendBillByWhatsApp($transactiondata, $order,$msg)
    { 
        $msg = str_replace(' ','-',$msg); 
        $filename = 'bill-'.time().'.pdf'; 
        $pdf = PDF::loadView('pdf.sale-bill', compact('transactiondata', 'order','msg'));
        $savePdf=  Storage::put('/sale-bills/'.$filename, $pdf->output());
        $endpoint = config('services.wati.endpoint'); 
        $token = config('services.wati.access_token'); 
        $pdf_link = url('/storage/sale-bills/'.$filename);

        $message ='Your Invoice Send Successfully'.$pdf_link;
        // $url = "http://api.bulkwhatsapp.net/wapp/api/send?apikey=dc7d6c96e48d4845abad17293a896ac0&mobile=".$order->party->phone."&msg=".$msg."&pdf=".$pdf_link;

        $data = array(
            'number' => '91'.$order->party->phone,
            'type' => 'media',
            'message' => $msg,
            'media_url'=> $pdf_link,
            'instance_id' => '64E8D96480F1F',
            'access_token' => '64e5d97a420fa'
        );
        $response = Http::post('https://hisocial.in/api/send', $data);
        // $url = 'https://api.bulkwhatsapp.net/wapp/api/send?apikey=dc7d6c96e48d4845abad17293a896ac0&mobile='.$order->party->phone.'&msg=testsmg'.'&pdf='.$pdf_link;
  //          $message ='Your Invoice Send Successfully'.$pdf_link;
  // $url = 'https://api.bulkwhatsapp.net/wapp/api/send?apikey=681eeacd6f764595a2098f10b7027162&mobile='.$order->party->phone.'&msg='.$pdf_link.$order->order_number.'/'.$total;

    //  $ch = curl_init();
    //  curl_setopt($ch, CURLOPT_URL, $url);
    //  curl_setopt($ch, CURLOPT_POST, 0);
    //  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //  $response = curl_exec ($ch);
    //  $err = curl_error($ch);  //if you need
    //  curl_close ($ch); 
    // return $response;


        // $response = Http::withToken($token)
        //     ->post($endpoint.'/api/v1/sendTemplateMessage?whatsappNumber=+919588838446', [
        //         'template_name' => 'order_bill_pdf',
        //         'broadcast_name' => 'order_bill_pdf',
        //         'parameters' => [
        //             [ 'name' => 'pdf_link', 'value' => $pdf_link ],
        //             [ 'name' => 'name', 'value' => $order->party->name ],
        //             [ 'name' => 'bill_number', 'value' => 'BILL-1001' ],
        //             [ 'name' => 'order_number', 'value' => 'SO-'.$order->order_number ],
        //             // [ 'name' => 'wa_narration', 'value' => 'SO-'.$transaction->transaction->wa_narration ]
        //         ]
        //     ]); 


        // if($response->successful()){
        //     Storage::delete($pdf_link);
        // }    
        // return $response;   

    }

}