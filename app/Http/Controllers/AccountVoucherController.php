<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\Party;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\AccountVoucherRequest;
use Illuminate\Support\Facades\DB;

class AccountVoucherController extends Controller
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
        $parties = Party::orderBy('name')->pluck('name', 'id');
        $items = Item::orderBy('name')->pluck('name');
        return view('account-vouchers.create', compact('parties', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountVoucherRequest $request)
    {

        $description = $request->narration;
        $item = $request->item ?? '';

        if($request->filled('quantity') && $request->filled('rate')){
            $description = "{$request->quantity} - {$item}  @ {$request->rate} $description";
        }

        DB::transaction(function() use($request, $description){
 
           $transactionNumber = Transaction::transactionNumber();

            Transaction::create([
                'transaction_date' => $request->transaction_date,
                'type' => 'acc',
                'debitor_id' => $request->dr_party,
                'creditor_id' => $request->cr_party,
                'amt_debt' => $request->amount,
                'narration' => $description,
                'transaction_number' => $transactionNumber,
            ]);
        
            Transaction::create([
                'transaction_date' => $request->transaction_date,
                'type' => 'acc',
                'debitor_id' => $request->cr_party,
                'creditor_id' => $request->dr_party,
                'amt_credit' => $request->amount,
                'narration' => $description,
                'transaction_number' => $transactionNumber,
            ]);
        }); 

        return  back()->with('success', 'Account Voucher Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $parties = Party::orderBy('name')->pluck('name', 'id');
        
        $amount = $transaction->amt_debt > 0 
                ? $transaction->amt_debt 
                : $transaction->amt_credit;                   

        return view('account-vouchers.edit', compact('transaction', 'parties', 'amount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(AccountVoucherRequest $request, Transaction $transaction)
    {
           
        DB::transaction(function() use($request, $transaction){

            $transactionNumber = Transaction::transactionNumber();
        
            Transaction::create([
                'transaction_date' => $request->transaction_date,
                'type' => 'acc',
                'debitor_id' => $request->dr_party,
                'creditor_id' => $request->cr_party,
                'amt_debt' => $request->amount,
                'narration' => $request->narration,
                'transaction_number' => $transactionNumber,
                'order_id' => $transaction->order_id ?? null,
                'transfer_id' => $transaction->transfer_id ?? null
            ]);
        
            Transaction::create([
                'transaction_date' => $request->transaction_date,
                'type' => 'acc',
                'debitor_id' => $request->cr_party,
                'creditor_id' => $request->dr_party,
                'amt_credit' => $request->amount,
                'narration' => $request->narration,
                'transaction_number' => $transactionNumber,
                'order_id' => $transaction->order_id ?? null,
                'transfer_id' => $transaction->transfer_id ?? null
            ]);
    
            Transaction::where([
                ['transaction_number', $transaction->transaction_number],
                ['type', $transaction->type],
            ])->delete(); 
            
        });        
        
        return  redirect()->route('dashboard')->with('success', 'Account Voucher Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Transaction $transaction)
    {  
        Transaction::where([
            ['transaction_number', $transaction->transaction_number],
            ['type', $transaction->type],
        ])->delete();  
            
        return back()->with('success', 'Account Voucher Deleted');
    }
}
