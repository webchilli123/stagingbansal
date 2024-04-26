<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\TransferTransaction;
use App\Models\Party;
use App\Models\Item;
use App\Models\Order;
use App\Models\Process;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransferReceiveRequest;
use App\Services\PartyStockService;

class TransferReceiveController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        $available_items = null; 
        $items = Item::orderBy('name')->pluck('name', 'id');
        $parties = Party::orderBy('name')->pluck('name', 'id');
        $processes = Process::orderBy('name')->pluck('name', 'id');
        $transfer_number = Transfer::transferNumber();
        $orders = Order::with('party')
                        ->orderBy('order_number')
                        ->select('order_number','type', 'id', 'party_id')
                        ->where('type', 'purchase')
                        ->get();    
                        
        if ($request->filled('sender_id')) {
    
            $available_items = (new PartyStockService)->getAvailableItemsClient($request->sender_id);  
            // dd($available_items);
            if(count($available_items) == 0){
                return back()->withErrors(['message' => 'No Stock Found']);
            }

        }                   
        //$available_items = (new PartyStockService)->getAvailableItems($request->sender_id);  
       return view('transfers.receive',
        compact('items', 'transfer_number', 'orders', 'parties', 'processes', 'available_items'));
    }

    public function store(TransferReceiveRequest $request)
    {     
        DB::transaction(function() use($request){


            $transfer = Transfer::create([
                'transfer_date' => $request->transfer_date,
                'transfer_number' => Transfer::transferNumber(),
                // 'order_id' => $request->order_id,
                'sender_id' => $request->sender_id,
                'receiver_id' => Party::SELF_STORE,
                // 'process_id' => $request->process_id,
                'is_receive' => 1,
                'narration' => $request->narration,
            ]);

            $transfer->updateStockOnReceive($request);
            $transfer->createReceiveAccountVoucher($request);

        });

        return redirect()->route('transfers.receive.create')->with('success', 'Items Received');

    }
}
