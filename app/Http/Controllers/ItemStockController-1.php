<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferTransaction;
use App\Models\Party;
use App\Models\Item;
use DB;

class ItemStockController extends Controller
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
        $condations = null;
        $where = '';
        $parties = Party::orderBy('name')->pluck('name', 'id');
        $items = Item::orderBy('name')->pluck('name', 'id');

        // $search = "name";
        //  $result = Item::where('name','LIKE','%'.$search'%')->get();
        if (!empty($request->name)) {
            $condations = "WHERE items.name=" . "'$request->name' Group By item_id,party_id";
        }
        // dd($condations);

        if (!empty($request->item_id)) {
            $where = 'AND transfer_transactions.item_id = ' . $request->item_id;
        }

        $transactions = DB::select("select items.id,items.name,items.quantity,parties.name as Pname FROM items LEFT JOIN transfer_transactions on items.id = transfer_transactions.item_id LEFT JOIN parties on transfer_transactions.party_id = parties.id  $where $condations AND party_id = 3977 Group By name");
        //$transactions = DB::select("select items.id,items.name,items.quantity,parties.name as Pname FROM items LEFT JOIN transfer_transactions on items.id = transfer_transactions.item_id LEFT JOIN parties on transfer_transactions.party_id = parties.id  $where $condations AND party_id = 3977");
        // dd($transactions);

        return view('reports.item-stock', compact('transactions', 'parties', 'items'));
    }
}

















 // <?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\TransferTransaction;
// use App\Models\Party;
// use App\Models\Item;

// class ItemStockController extends Controller
// {
//     /**
//      * Handle the incoming request.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function __invoke(Request $request)
//     {
//         $transactions = null;
//         $parties = Party::orderBy('name')->pluck('name', 'id');
//         $items = Item::orderBy('name')->pluck('name', 'id');
    
//         if ($request->filled('item_id')) {
          
//             $transactions = TransferTransaction::where('item_id', $request->item_id)
//             ->with('item')
//             ->selectRaw('item_id, party_id, SUM(quantity) AS total_quantity')
//             ->groupBy('item_id', 'party_id')
//             ->paginate(50)
//             ->withQueryString();

//             if (count($transactions) == 0) {
//                 return redirect()->route('item-stock.index')->withErrors(['message' => 'No Item Found']);
//             }

//             return view('reports.item-stock', compact('transactions', 'parties', 'items'));
//         }

//         $transactions = TransferTransaction::with([
//           'item', 
//           'party' => function($query){ $query->select('id', 'name'); } 
//           ])
//           ->selectRaw('item_id , party_id, SUM(quantity) AS total_quantity')
//           ->groupBy('item_id', 'party_id')
//           ->having('total_quantity', '>', 0)
//           ->paginate(50)
//           ->withQueryString();

//         return view('reports.item-stock', compact('transactions', 'parties', 'items'));
//     }
// }
