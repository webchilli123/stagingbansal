<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransferTransaction;
use App\Services\SaleBillService;
use Illuminate\Http\Request;

class OrderWhatsappController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Order $order)
    {
        $msg = $request->narration ?? ''; 
        $order->load(['orderItems.item']);
        // if($order->transferTransactions()->count() > 0){
            $order->load([
            //    'transferTransactions.item', 
            //    'transferTransactions.transaction', 
            //    'transactions' => function($query){ 
                //        $query->where('amt_debt', '>', 0)
            //        ->orderBy('transaction_date', 'desc'); 
            //    },
              
            //    'transferTransactions.transport',
               'party',
           ]);

    //    }
    $transaction =  TransferTransaction::where('transaction_id', $request->transaction_id)
        // ->whereNotNull('transaction_id')
                        ->with([
                            'item', 
                            'transport',
                            'order',
                            'transaction'
                            ])->get();
                            // return $order;
        // return $transaction;
            $transactiondata = $transaction; 
            if(!empty($request->show_preview) && $request->show_preview == 'true'){
                return view('pdf.sale-bill', compact('transactiondata', 'order','msg'));
            }
            if (isset($order->party->phone)) {
                $response = (new SaleBillService)->sendBillByWhatsapp($transaction, $order,$msg);
                
                // if ($response->failed()) {
                    //     return redirect()->withErrors(['message' => 'Something went wrong with whatsapp.']);
                    // }
                }
        return back()->with('success', "Order Bill Send Successfully on wahtsApp");
        // dd($order->transactions);
        // $total_amount = $order->orderItems->sum('total_price');
    }

    
}
