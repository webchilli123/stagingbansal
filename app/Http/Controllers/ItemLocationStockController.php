<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferTransaction;
use App\Models\Item;
use App\Models\Party;

class ItemLocationStockController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $transactions = [];
        $parties = Party::orderBy('name')->pluck('name', 'id');
        $items = Item::orderBy('name')->pluck('name', 'id');
    
        if ($request->filled('item_id')) {
          
            $transactions = TransferTransaction::where('item_id', $request->item_id)
            ->with('item')
            ->selectRaw('item_id, party_id, SUM(quantity) AS total_quantity')
            ->groupBy('item_id', 'party_id')
            ->paginate(50)
            ->withQueryString();

            if (count($transactions) == 0) {
                return redirect()->route('itemLocationStock.index')->withErrors(['message' => 'No Item Found']);
            }

            return view('reports.itemLocationStock', compact('transactions', 'items', 'parties'));
        }

        return view('reports.itemLocationStock', compact('transactions', 'items', 'parties'));
    }
}

