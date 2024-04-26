<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Party;
use Illuminate\Http\Request;
use App\Http\Requests\MultipleAccountVoucherRequest;
use Illuminate\Support\Facades\DB;

class MultipleAccountVoucherController extends Controller
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
        return view('account-vouchers.multiple-create', compact('parties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MultipleAccountVoucherRequest $request)
    {
        
        if($request->drcr != 'DR' && $request->drcr != 'CR'){
            return back()->withErrors(['message' => 'DR OR CR is required.'])->withInput();
        }

        DB::transaction(function() use($request){


            foreach ($request->parties as $i => $party) {
                
                $transactionNumber = Transaction::transactionNumber();

                Transaction::create([
                    'transaction_date' => $request->transaction_date,
                    'type' => 'acc',
                    'debitor_id' =>  $request->drcr == 'DR' ? $request->account_id : $party,
                    'creditor_id' => $request->drcr == 'DR' ? $party : $request->account_id,
                    'amt_debt' => $request->amounts[$i],
                    'narration' => $request->narrations[$i],
                    'transaction_number' => $transactionNumber
                ]);

                Transaction::create([
                    'transaction_date' => $request->transaction_date,
                    'type' => 'acc',
                    'debitor_id' =>  $request->drcr == 'DR' ? $party : $request->account_id ,
                    'creditor_id' => $request->drcr == 'DR' ? $request->account_id : $party,
                    'amt_credit' => $request->amounts[$i],
                    'narration' => $request->narrations[$i],
                    'transaction_number' => $transactionNumber,
                ]);

            }

        });


        return  back()->with('success', 'Account Vouchers Created');
    }

}
