<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\TransferTransaction;
use DB;
class PartyStockController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {  
        $transactions = null;
        if ($request->filled('party_id')) { 
            $transactions = TransferTransaction::where('party_id', $request->party_id) 
            // ->whereNotIn('party_id', [1001])
                ->orderBy('id','asc')
                ->paginate(500);   
                $transactions = $this->_group_by($transactions,'item_id'); 
                if(!empty($transactions)){ 
                    foreach($transactions as $tr){
                        foreach($tr as $r){ 
                            $r->itemname = \App\Models\Item::where('id',$r->item_id)->first();
                            $op = DB::table('opening_stock')->where('item_name',$r->item_id)->where('party_name',$r->party_id)->first();  
                            if(!empty($op)){
                                $r->openingQty =( (float)$op->quantity ?? 0); 
                            }
                            $r->material = \App\Models\Item::where('id',$r->material_id)->first();
                            $r->transfer = \App\Models\Transfer::where('id',$r->transfer_id)->first();
                        }
                    }
                } 
            if(count($transactions) == 0){
                return redirect()->back()->withErrors(['message' => 'No Stock Found']);
            } 
        } 
        $parties = Party::orderBy('name')->pluck('name', 'id'); 
        return view('reports.party-stock', compact('parties', 'transactions'));
    }
    function _group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
}
