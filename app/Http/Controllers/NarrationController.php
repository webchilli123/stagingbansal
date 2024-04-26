<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class NarrationController extends Controller
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
        if (!empty($request->from_date) && !empty($request->to_date)) {
            $transactions = Transaction::whereBetween('transaction_date', [$request->from_date,$request->to_date])
            ->whereNotNull('wa_narration')
                ->paginate(500);
          
            if(count($transactions) == 0){
                return redirect()->back()->withErrors(['message' => 'No Stock Found']);
            }

        }

        $items = Transaction::orderBy('transaction_date')->pluck('transaction_date', 'id');
    

        return view('reports.narration', compact('items', 'transactions'));
    }
}
