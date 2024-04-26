<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\TransferTransaction;
use DB;
class ItemwisestockCntroller extends Controller
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
        if ($request->filled('item_id')) {

            // $transactions = TransferTransaction::where('party_id', $request->party_id)
            //     ->with('item')
            //     ->selectRaw('item_id , SUM(quantity) AS total_quantity')
            //     ->groupBy('item_id')
            //     ->having('total_quantity', '>', 0)
            //     ->paginate(50)
            //     ->withQueryString();

            // $transactions = TransferTransaction::where('item_id', $request->item_id)
            //     ->orderBy('party_id','desc')
            //     ->paginate(500);
                // dd($transactions);
            if ($request->filled('item_id')) { 
                $transactions = TransferTransaction::where('item_id', $request->item_id)
                ->whereNotIn('party_id', [4029,1001])
                ->orderBy('created_at','desc')
                ->paginate(500);
                dd($transactions);
                
                    $transactions = $this->_group_by($transactions,'party_id');   
                if(!empty($transactions)){
                    foreach($transactions as $tr){
                        foreach($tr as $k=> $r){ 
                            $r->itemname = \App\Models\Item::where('id',$r->item_id)->first(); 
                            $opening = 0;
                            if($k == 0){
                                $op = DB::table('opening_stock')->where('item_name',$r->item_id)->where('party_name',$r->party_id)->first(); 
                                $opening = $op->quantity ?? 0;
                            }   
                            $r->openingQty = (int)$opening;
                            $r->material = \App\Models\Item::where('id',$r->material_id)->first();
                            $r->transfer = \App\Models\Transfer::where('id',$r->transfer_id)->first();
                            $r->party = \App\Models\Party::where('id',$r->party_id)->first();  
                            $r->worker = \App\Models\Party::where('id',$r->worker_id)->first();
                        }
                    }
                }  
                /* if(count($transactions) == 0){
                    return redirect()->back()->withErrors(['message' => 'No Stock Found']);
                }  */
            } 
          
           /*  if(count($transactions) == 0){
                return redirect()->back()->withErrors(['message' => 'No Stock Found']);
            } */

        }

        $items = Item::select('name', 'id')->orderBy('name')->get();
     
        return view('reports.itemWiseStock', compact('items', 'transactions'));
    }
    function _group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
}