<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Transport;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderTransferRequest;
use App\Services\SaleBillService;

class OrderTransferController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Order $order)
    {
        $parties = Party::select('id','name')->get();
        $order->load('orderItems', 'orderItems.item');


        $transports = Transport::orderBy('name')->pluck('name', 'id');
        $isreturn = $request->return ?? '';
        return view('orders.transfer', compact('order', 'transports','isreturn','parties'));
    }

    public function store(OrderTransferRequest $request, Order $order)
    {
        $order->load('orderItems');
        // dd($request->type == 'return' && $order->type == 'sale');
        if ($request->type == 'return' && $order->type == 'sale') {
            DB::transaction(function () use ($request, $order) {
                $tr = $order->createReturnSaleAccountVoucher($request);
                $order->updateReturnStock($request,$tr);
            });
            return back()->with('success', "Order Items returned");
        }
        if ($request->type == 'return' && $order->type == 'purchase') {
            DB::transaction(function () use ($request, $order) {
                $tr = $order->createReturnPurchaseAccountVoucher($request);
                $order->updateReturnStock($request,$tr);
             });
            return back()->with('success', "Order Items returned");
        }
        if ($order->type == Order::SALE) {
            $transactiondata = $request->all();
            if(isset($order->party->phone)){
                //$response = (new SaleBillService)->sendBillByWhatsapp($transactiondata, $order);
                        // dd($response->failed());
               /*  if($response){
                }else{ */
                    // return redirect()->withErrors(['message' => 'Something went wrong with whatsapp.']);
                /* } */
            }

            // dd('message sent');

            DB::transaction(function () use ($request, $order) {
                $tr = $order->createSaleAccountVoucher($request);
                $order->updateStock($request,$tr);
            });

        }

        if ($order->type == Order::PURCHASE) {

            DB::transaction(function () use ($request, $order) {
                $tr = $order->createPurchaseAccountVoucher($request);
                $order->updateStock($request,$tr);
             });
        }


        if ($request->filled('order_narration')) {
            $order->update(['narration' => $request->order_narration]);
        }

        $order->updateOrderStatus();

        $order->type == Order::SALE
        ? $type = 'Sold'
        : $type = 'Purchased';

        return back()->with('success', "Order Items $type");
    }
}
