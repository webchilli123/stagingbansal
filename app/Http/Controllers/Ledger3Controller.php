<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class Ledger3Controller extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
               
       if ($request->ajax() 
           && $request->filled('party_id') 
           && !auth()->user()->can('viewAny', App\Models\Transaction::class)) {

           $party = Party::findOrFail($request->party_id);

           if(auth()->id() !== $party->user_id){
               return ['message' => 'You are not allowed.'];
           }
   
       }


        // get last 30 days vouchers
        $date = date('Y-m-d', strtotime('-30 days'));


        // account ledger
        if ($request->ajax() && $request->type == 'account' && $request->filled('party_id')) {
           
            $vouchers =  DB::table('transactions')->where([
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
              ->where('transaction_date', '>=', $date)    
              ->orderBy('transaction_date')
              ->orderBy('id')
              ->get();
  
            $debits = DB::table(function($query) use($request, $date){
                $query->select('amt_debt')
                    ->from('transactions')
                    ->where([
                        ['debitor_id',  $request->party_id],
                        ['type', 'acc'],
                        ['transaction_date', '<', $date]
                    ]);
            }, 'debits')->sum('amt_debt');


            $credits = DB::table(function($query) use($request, $date){
                $query->select('amt_credit')
                    ->from('transactions')
                    ->where([
                        ['debitor_id',  $request->party_id],
                        ['type', 'acc'],
                        ['transaction_date', '<', $date]
                    ]);
            }, 'credits')->sum('amt_credit');
        
            // preivous vouchers total balance
            $balance = $credits - $debits;
              
            return [
                'balance' => $balance,
                'vouchers' => $vouchers
            ];

        }

        // stock ledger
        if ($request->ajax() && $request->type == 'stock' && $request->filled('party_id')) {
           
            $vouchers = DB::table('transactions')->where([
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
              ->where('transaction_date', '>=', $date)    
              ->orderBy('transaction_date')
              ->orderBy('id')
              ->get();

            $debits = DB::table(function($query) use($request, $date){
                $query->select('stock_debt')
                    ->from('transactions')
                    ->where([
                        ['debitor_id',  $request->party_id],
                        ['type', 'inv'],
                        ['transaction_date', '<', $date]
                        ])
                    ->orderBy('transaction_date')
                    ->orderBy('id');
            }, 'debits')->sum('stock_debt');


            $credits = DB::table(function($query) use($request, $date){
                $query->select('stock_credit')
                    ->from('transactions')
                    ->where([
                        ['debitor_id',  $request->party_id],
                        ['type', 'inv'],
                        ['transaction_date', '<', $date]
                        ])
                    ->orderBy('transaction_date')
                    ->orderBy('id');
            }, 'credits')->sum('stock_credit');
        
            // preivous vouchers total balance
            $balance = $credits - $debits;
              
            return [
                'balance' => $balance,
                'vouchers' => $vouchers
            ];

        }
        $userDetail = auth()->user();
        if($userDetail->role_id == 1){
            $parties = Party::orderBy('name')->pluck('name', 'id'); 
        }else{
            $parties = Party::where('user_id',$userDetail->id)->orderBy('name')->pluck('name', 'id'); 
        }
        
        return view('ledger3.index', compact('parties'));
    }
}