<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionMarkController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {  

        if($request->isMethod('GET')){
            return abort(404);
        }

        $request->validate([
            'type' => 'required|string',
            'party_id' => 'required|integer',
            'transaction_date' => 'required|date',
        ]);
 
        $request->type == 'account' 
            ? $type = 'acc'
            : $type = 'inv';

       Transaction::where('type', $type)
            ->where('transaction_date', '<=', $request->transaction_date)
            ->where('is_paid', 0)
            ->where(function($query) use($request){ 
                $query->where('debitor_id', $request->party_id)
                ->orWhere('creditor_id', $request->party_id);
            })
            ->update(['is_paid' => 1]);

       return back()->with('success', 'Transactions marked as Paid');
    }
}
