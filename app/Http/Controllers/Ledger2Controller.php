<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class Ledger2Controller extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
               
        $this->authorize('viewAny', Transaction::class);

        // account ledger
        if ($request->ajax() && $request->type == 'account' && $request->filled('party_id')) {
           
            return  DB::table('transactions')->where([
                ['debitor_id',  $request->party_id],
                ['transactions.type', 'acc']
              ])
              ->leftJoin('parties', 'transactions.creditor_id', '=', 'parties.id')
              ->select(
                  'transactions.id', 
                  'transactions.transaction_date',
                  'transactions.creditor_id', 
                  'transactions.amt_debt', 
                  'transactions.amt_credit', 
                  'transactions.narration',
                  'parties.name as party_name')
              ->orderBy('transaction_date')
              ->orderBy('id')
              ->cursorPaginate(300);
        }

        // stock ledger
        if ($request->ajax() && $request->type == 'stock' && $request->filled('party_id')) {
           
            return  DB::table('transactions')->where([
                ['debitor_id',  $request->party_id],
                ['transactions.type', 'inv']
              ])
              ->leftJoin('parties', 'transactions.creditor_id', '=', 'parties.id')
              ->select(
                  'transactions.id', 
                  'transactions.transaction_date',
                  'transactions.creditor_id', 
                  'transactions.stock_debt', 
                  'transactions.stock_credit', 
                  'transactions.narration',
                  'parties.name as party_name')
              ->orderBy('transaction_date')
              ->orderBy('id')
              ->cursorPaginate(300);
        }
        
        $userDetail = auth()->user();
        if ($userDetail->role_id == 1) {
            $parties = Party::orderBy('name')->pluck('name','id');
        }else{
            $parties = Party::where('user_id',$userDetail->id)->orderBy('name')->pluck('name','id');
        }
        return view('ledger2.index', compact('parties'));
    }
}