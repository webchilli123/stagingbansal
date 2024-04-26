<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class LedgerController extends Controller
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

        $parties = Party::orderBy('name')->pluck('name', 'id'); 


        // account ledger
        if ($request->type == 'account' && $request->filled('party_id')) {

            Party::findOrFail($request->party_id);

            $account_transactions = DB::table('transactions')->where([
                ['debitor_id',  $request->party_id],
                ['transactions.type', 'acc'],
            ])
            ->leftJoin('parties', 'transactions.creditor_id', '=', 'parties.id')
            ->select(
                'transactions.id', 
                'transactions.transaction_date',
                'transactions.creditor_id', 
                'transactions.amt_debt', 
                'transactions.amt_credit', 
                'transactions.is_paid', 
                'transactions.narration',
                'transactions.order_id',
                'transactions.transfer_id',
                'parties.name as party_name')
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->paginate(100)
            ->withQueryString();

            if($account_transactions->onFirstPage()){
                $balance = 0;
            }else{
                
                $limit = ($account_transactions->currentPage() - 1) * $account_transactions->perPage();
                
                // previous debits and credits

                $debits =  DB::table(function($query) use($request, $limit){
                     $query->select('amt_debt')->from('transactions')->where([
                        ['debitor_id',  $request->party_id],
                        ['type', 'acc']
                        ])
                        ->orderBy('transaction_date')
                        ->orderBy('id')
                        ->limit($limit);
                }, 'debits')->sum('amt_debt');

                
                $credits = DB::table(function($query) use($request, $limit){
                    $query->select('amt_credit')->from('transactions')->where([
                       ['debitor_id',  $request->party_id],
                       ['type', 'acc']
                       ])
                       ->orderBy('transaction_date')
                       ->orderBy('id')
                       ->limit($limit);
               }, 'credits')->sum('amt_credit');

                $balance = $credits - $debits;
            }

            return view('ledger.index', compact('parties', 'account_transactions', 'balance'));
        }

        // stock ledger
        if ($request->type == 'stock' && $request->filled('party_id')) {
    
            $stock_transactions = DB::table('transactions')->where([
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
                'transactions.is_paid', 
                'transactions.narration',
                'parties.name as party_name')
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->paginate(100)
            ->withQueryString();

            if($stock_transactions->onFirstPage()){
                $balance = 0;
            }else{
                
                $limit = ($stock_transactions->currentPage() - 1) * $stock_transactions->perPage();
                
                $debits = DB::table(function($query) use($request, $limit){
                    $query->select('stock_debt')->from('transactions')->where([
                        ['debitor_id',  $request->party_id],
                        ['type', 'inv']
                        ])
                        ->orderBy('transaction_date')
                        ->orderBy('id')
                        ->limit($limit);
                }, 'debits')->sum('stock_debt');

                $credits = DB::table(function($query) use($request, $limit){
                    $query->select('stock_credit')->from('transactions')->where([
                        ['debitor_id',  $request->party_id],
                        ['type', 'inv']
                        ])
                        ->orderBy('transaction_date')
                        ->orderBy('id')
                        ->limit($limit);
                }, 'credits')->sum('stock_credit');
            
                $balance = $credits - $debits;
            }

            return view('ledger.index', compact('parties', 'stock_transactions', 'balance'));
        }

        return view('ledger.index', compact('parties'));
    }
}
