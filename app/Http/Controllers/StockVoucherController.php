<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Party;
use App\Models\Item;
use App\Models\TransferTransaction;
use Illuminate\Http\Request;
use App\Http\Requests\StockVoucherRequest;
use Illuminate\Support\Facades\DB;

class StockVoucherController extends Controller
{
    
    public function __construct()
    {
        $this->authorizeResource(Transaction::class, 'transaction');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        return redirect()->route('dashboard');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    
        $parties = Party::orderBy('name')->pluck( 'name', 'id');
        $items = Item::orderBy('name')->pluck('name', 'id');
        return view('stock-vouchers.create', compact('parties', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StockVoucherRequest $request)
    {

        $item = Item::findOrFail($request->item_id);

        if(!isset($item)){
            return back()->withErrors(['message' => 'Invalid Item']);
        }
    

        DB::transaction(function() use($request){

           $transaction = Transaction::createStockVoucher($request);            
           $transaction->updateStock($request);            

        });    

        return  back()->with('success', 'Stock Voucher Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $item)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {

        $parties = Party::orderBy('name')->pluck( 'name', 'id');        
        $items = Item::orderBy('name')->pluck('name', 'id');

        $amount = $transaction->stock_debt > 0
            ? $transaction->stock_debt
            : $transaction->stock_credit;

        if($transaction->stock_debt > 0){
           $debitor_id = $transaction->debitor_id;
           $creditor_id = $transaction->creditor_id;
        }        
        
        if($transaction->stock_credit > 0){
            $debitor_id = $transaction->creditor_id;
            $creditor_id = $transaction->debitor_id;
         }   
         
        return view('stock-vouchers.edit', 
        compact('items', 'parties', 'transaction', 'amount', 'creditor_id', 'debitor_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(StockVoucherRequest $request, Transaction $transaction)
    {
        
        DB::transaction(function() use($request, $transaction){

            $new_transaction = Transaction::createStockVoucher($request);
            $new_transaction->updateStock($request);       
                 
            $transaction_id = Transaction::where('transaction_number', $transaction->transaction_number)->max('id'); 
            TransferTransaction::where('transaction_id', $transaction_id)->delete();
    
            Transaction::where([
                ['transaction_number', $transaction->transaction_number],
                ['type', $transaction->type],
            ])->delete();    
    
        });
    
        return  redirect()->route('dashboard')->with('success', 'Stock Voucher Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        // delete transfer transaction
        $transaction_id = Transaction::where('transaction_number', $transaction->transaction_number)->max('id'); 
        TransferTransaction::where('transaction_id', $transaction_id)->delete();

        Transaction::where([
            ['transaction_number', $transaction->transaction_number],
            ['type', $transaction->type],
        ])->delete(); 

        return redirect()->route('ledger.index')->with('success', 'Stock Voucher  Deleted');
    }
}
