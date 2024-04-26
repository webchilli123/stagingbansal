<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransferTransaction;
use Illuminate\Support\Facades\DB;
use App\Services\PartyStockService;
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
        
        $data = TransferTransaction::where('item_id',$request->item_id)->where('party_id',$party_id)->select('quantity','type','id')->get();
        $trasasctionQTY = 0; 
        $decreasedQTY = 0;  
        if(!empty($data)){
            foreach($data as $detail){  
                if(!empty($detail->type) && $detail->type == 'purchase' || !empty($detail->type) && $detail->type == 'receive'){
                    $trasasctionQTY += (float)$detail->quantity ?? 0;  
                } 
                if(!empty($detail->type) && $detail->type == 'sale' || !empty($detail->type) && $detail->type == 'transfer'){ 
                    $decreasedQTY += -(float)$detail->quantity ?? 0;   
                }
            }
        }  
        $left = Item::find($request->item_id);  
        $total =  $trasasctionQTY + (float)$left->quantity - $decreasedQTY;

        if(!empty($total)){ 
            return [
                'total_quantity'=>$total, 
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
    public function checkClient(Request $request)
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
        
        $allitems = TransferTransaction::where('party_id',$party_id)->groupBy('item_id')->get();  
        $arrayItem= [];
        $total = 0;
        foreach($allitems as $itemss){ 
            $data = TransferTransaction::where('item_id',$itemss['item_id'])->where('party_id',$party_id)->select('quantity','type','id','item_id','party_id','waste')->get();
            $trasasctionQTY = 0; 
            $decreasedQTY = 0;  
            $opening = 0;  
            $waste = 0;  
            if(!empty($data)){
                $item = Item::where('id',$itemss['item_id'])->first(); 
                foreach($data as  $k=>$detail){  
                    if($k == 0){
                        $op = DB::table('opening_stock')->where('item_name',$detail->item_id)->where('party_name',$detail->party_id)->first(); 
                        $opening += $op->quantity ?? 0;
                    }
                    if(!empty($detail->type) && $detail->type == 'purchase' || !empty($detail->type) && $detail->type == 'receive'){
                        $trasasctionQTY += (float)$detail->quantity ?? 0;  
                    } 
                    if(!empty($detail->type) && $detail->type == 'sale' || !empty($detail->type) && $detail->type == 'transfer'  || !empty($detail->type) && $detail->type == 'used'){ 
                        $decreasedQTY += -(float)$detail->quantity ?? 0;   
                    } 
                    //$waste += $detail->waste ?? 0;
                    $waste += abs($detail->waste * $detail->quantity / 100) ?? 0;  
                } 
                $itemss->name = $item->name ?? '';
                $qty = !empty($item->quantity) ? (int)$item->quantity : 0;
                $total += $opening + $trasasctionQTY  - $decreasedQTY -$waste ;
                // $arrayItem[] = $itemss; 
            } 
        }   
        if(!empty($total)){ 
            return [ 
                'total_quantity'=>$total, 
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
