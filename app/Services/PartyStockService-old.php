<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PartyStockService{
    

    public function getAvailableItems($party_id){

        return DB::table('transfer_transactions')
           ->where('party_id', $party_id) 
           ->join('items', 'items.id', '=', 'transfer_transactions.item_id')
           ->selectRaw('
            items.name AS item_name,
            transfer_transactions.item_id, 
            SUM(transfer_transactions.quantity+items.quantity) AS total_quantity
           ')
           ->groupBy('transfer_transactions.item_id')
           ->having('total_quantity', '>', 0)->orderBy('item_name')
           ->get();
    }

}

// DB::select("select items.id,items.name AS item_name,items.quantity,transfer_transactions.item_id, SUM(transfer_transactions.quantity + items.quantity)AS total_quantity FROM items LEFT JOIN transfer_transactions on items.id = transfer_transactions.item_id
//                     WHERE party_id=3000  Group By item_id");