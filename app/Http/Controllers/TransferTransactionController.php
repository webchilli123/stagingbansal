<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\TransferTransaction;
use App\Models\Item;
use App\Models\Party;


class TransferTransactionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {  
        // dd('here');
        $transactions = null;
        $parties = Party::orderBy('name')->pluck('name', 'id');
        $items = Item::orderBy('name')->pluck('name', 'id');
        // dd($items);
    
        if ($request->filled('party_id')) {

            
            $transactions = TransferTransaction::selectRaw("
                *,
               (CASE WHEN type = 'used' 
               THEN 
               (SELECT SUM(quantity) FROM transfer_transactions t2 
               WHERE
               t2.transfer_id IS NOT NULL AND
               t2.item_id = transfer_transactions.item_id AND
               t2.created_at <= transfer_transactions.created_at 
               ) ELSE 0 END) 
               AS balance_quantity
            ")
            ->where(function($query) use($request){
                return $query->where('item_id', $request->item_id)
                     ->where('party_id', @$request->party_id);
            })
            ->with('transfer')
            //->where('type', '!=', 'used')
            ->where(function($query) use($request){
                $query->where('party_id', $request->party_id)
                    ->orWhere('worker_id', $request->party_id,$request->name);
            })
            ->orderBy('created_at')
            ->orderBy('id')
            ->paginate(50)
            ->withQueryString();

            // if(count($transactions) == 0){
            //     return redirect()->back()->withErrors(['message' => 'No Stock Found']);
            // }

            // if (count($transactions) == 0) {

            //     return redirect()
            //         ->route('transfer-transactions.index')
            //         ->withErrors(['message' => 'No Transactions Found']);
            // }
        }
        // echo "<pre>";print_r($transactions);die;
        return view('reports.transfer-transactions', compact('transactions', 'parties', 'items'));
    }
}
