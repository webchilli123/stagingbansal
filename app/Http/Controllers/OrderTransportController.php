<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Order;

class OrderTransportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {  
        $orders = null;
       
        if ($request->filled('party_id')) {

            $orders = Order::with([
                    'transferTransactions.transport'
                 ])
                ->where('type', Order::SALE)
                ->where('party_id', $request->party_id)
                ->paginate(50)
                ->withQueryString();
          
            if(count($orders) == 0){
                return redirect()->route('order-transports.index')->withErrors(['message' => 'No Sale Order Found']);
            }

        }

        $parties = Party::orderBy('name')->pluck('name', 'id');

        return view('reports.order-transport', compact('parties', 'orders'));
    }
}
