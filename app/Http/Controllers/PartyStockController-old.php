<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\TransferTransaction;

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

            // $transactions = TransferTransaction::where('party_id', $request->party_id)
            //     ->with('item')
            //     ->selectRaw('item_id , SUM(quantity) AS total_quantity')
            //     ->groupBy('item_id')
            //     ->having('total_quantity', '>', 0)
            //     ->paginate(50)
            //     ->withQueryString();

            $transactions = TransferTransaction::where('party_id', $request->party_id)
                ->orderBy('party_id','asc')
                ->paginate(500);
          
            if(count($transactions) == 0){
                return redirect()->back()->withErrors(['message' => 'No Stock Found']);
            }

        }

        $parties = Party::orderBy('name')->pluck('name', 'id');
        // return $transactions;
        return view('reports.party-stock', compact('parties', 'transactions'));
    }
}
