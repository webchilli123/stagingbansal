<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransferTransaction;
use Illuminate\Support\Facades\DB;

class StockCheckController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {

        if(!$request->ajax() || blank($request->item_id) || blank($request->current_quantity)){
           return abort(404); 
        }
        
        $party_id = null;

        if($request->filled('party_id')){
            $party_id = $request->party_id;
        } else{
            $party_id = Party::SELF_STORE;
        }
        
        // item available in stock
        $item = TransferTransaction::where('item_id', $request->item_id)
        ->with('GetopStock')
        ->where('party_id', $party_id)
        ->with(['item', 'party'])
        ->selectRaw('item_id, party_id ,SUM(quantity) AS total_quantity')
        ->groupBy('item_id', 'party_id')
        ->first();
        
        $left = Item::find($request->item_id); 
        if(!empty($left && $left->quantity > 0)){
            return [
                'total_quantity' => (int)$left->quantity ?? 0,
                'item' => Item::find($request->item_id),
                'party' => Party::select('id', 'name')->find($party_id)
           ];
        }else{
           return [
                'total_quantity' => 0,
                'item' => Item::find($request->item_id),
                'party' => Party::select('id', 'name')->find($party_id)
           ];
        }

    }
}
