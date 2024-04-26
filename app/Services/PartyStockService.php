<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\TransferTransaction;
use App\Models\Item;

class PartyStockService{
    

    public function getAvailableItems($party_id){
        $allitems = Item::get(); 
        $arrayItem= [];
        foreach($allitems as $itemss){ 
            if(!empty($itemss->name)){  
                $data = TransferTransaction::where('item_id',$itemss['id'])->where('party_id',4029)->select('quantity','type','id')->get();
                
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
                }   
                $itemss->total =  $trasasctionQTY + $itemss->quantity - $decreasedQTY;
                $arrayItem[] = $itemss; 
            } 
        return $arrayItem;

        /* return DB::table('transfer_transactions')
           ->where('party_id', $party_id) 
           ->join('items', 'items.id', '=', 'transfer_transactions.item_id')
           ->selectRaw('
            items.name AS item_name,
            transfer_transactions.item_id, 
            SUM(transfer_transactions.quantity) AS total_quantity,
            transfer_transactions.id,openstock_id
           ')
           ->groupBy('transfer_transactions.item_id')
           ->having('total_quantity', '>', 0)->orderBy('item_name')
           ->get(); */
    }
    public function getAvailableItemsClient($party_id){ 
      /*   return DB::table('transfer_transactions')
           ->where('party_id', $party_id) 
           ->join('items', 'items.id', '=', 'transfer_transactions.item_id')
           ->selectRaw('
            items.name AS item_name,
            transfer_transactions.item_id, 
            SUM(transfer_transactions.quantity) AS total,
            transfer_transactions.id,openstock_id
           ')
           ->groupBy('transfer_transactions.item_id')
           ->having('total', '>', 0)->orderBy('item_name')
           ->get(); */
        $allitems = TransferTransaction::where('party_id',$party_id)->groupBy('item_id')->get(); 
        $arrayItem= [];
        foreach($allitems as $itemss){ 
            $data = TransferTransaction::where('item_id',$itemss['item_id'])->where('party_id',$party_id)->select('quantity','type','id','item_id','party_id','waste')->get();
            $waste = 0;
            $trasasctionQTY = 0; 
            $decreasedQTY = 0;  
            $opening = 0;  
            if(!empty($data)){
                $item = Item::where('id',$itemss['item_id'])->first(); 
                if(!empty($item)){
                    foreach($data as $k=>$detail){  
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
                        $waste += abs($detail->waste * $detail->quantity / 100) ?? 0;  
                    } 
                    // dd($opening );
                    $itemss->name = $item->name ?? '';
                    $qty = !empty($item->quantity) ? (int)$item->quantity : 0;
                    $itemss->total = $opening + $trasasctionQTY  - $decreasedQTY -$waste ;
                    $arrayItem[] = $itemss; 
                } 
            }    
        }
        return $arrayItem;
    }

}


// DB::select("select items.id,items.name AS item_name,items.quantity,transfer_transactions.item_id, SUM(transfer_transactions.quantity + items.quantity)AS total_quantity FROM items LEFT JOIN transfer_transactions on items.id = transfer_transactions.item_id
//                     WHERE party_id=3000  Group By item_id");