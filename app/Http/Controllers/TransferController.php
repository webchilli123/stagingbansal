<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\TransferItem;
use App\Models\TransferTransaction;
use App\Models\Party;
use App\Models\Item;
use App\Models\Process;
use App\Models\Order;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransferRequest;
use App\Services\PartyStockService;
use Session;
class TransferController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Transfer::class, 'transfer');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        if ($request->ajax()) {

            $transfers = Transfer::with(['sender', 'receiver'])->select();
  
            return DataTables::of($transfers)
            
                ->editColumn('transfer_number', function ($transfer) {
                    return 'T-'.$transfer->transfer_number;
                })
                 ->editColumn('transfer_date', function ($transfer) {
                     return $transfer->transfer_date->format('d M, Y') ;
                 })
                ->editColumn('is_receive', function ($transfer) {
                    return $transfer->is_receive ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($transfer) {
                    return view('transfers.buttons')->with(['transfer' => $transfer]);
                })->make(true);
        }   

        return view('transfers.index');
    }


    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        $available_items = null;      
        $getItem = null;      
        $parties = Party::orderBy('name')->pluck('name', 'id');
        $processes = Process::orderBy('name')->pluck('name', 'id');
        $transfer_number = Transfer::transferNumber();
        $orders = Order::with(['party'])
                        ->orderBy('order_number')
                        ->select('order_number','type', 'id', 'party_id')
                        ->where('type', 'purchase')
                        ->get();                
                            
        if ($request->filled('sender_id')) { 
            $available_items = (new PartyStockService)->getAvailableItems($request->sender_id); 
            // dd($available_items);
            if(count($available_items) == 0){ 
                Session::put('message', 'No Stock Found');
                return redirect()->back();
            }

           $getItem = Item::where('quantity','>', 0)->get();

        }
        
        return view('transfers.create', 
        compact('parties', 'processes', 'orders', 'transfer_number', 'available_items','getItem'));
    }

    public function store(TransferRequest $request)
    {
       
       $request->validated();
        
        DB::transaction(function () use ($request) {

            $transfer = Transfer::create([
                'transfer_date' => $request->transfer_date,
                'transfer_number' => Transfer::transferNumber(),
                'order_id' => $request->order_id,
                'receiver_id' => $request->receiver_id,
                'sender_id' => $request->sender_id,
                'process_id' => $request->process_id,
                'is_receive' => 0,
                'narration' => $request->narration,
            ]);

            $transfer->updateStockOnTransfer($request);
        });

        return redirect()->route('transfers.create')->with('success', "Items Transfered");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function show(Transfer $transfer)
    {
        
        $transfer->load([
            'transferTransactions.item',
            'transferTransactions.party' => function($query){ $query->select('id', 'name'); } 
        ]);

        if(isset($transfer->order_id)){
            $transfer->load(['order' => function($query){ $query->select('id', 'order_number'); }]);
        }

        if($transfer->is_receive){
            $transfer->load([
                'transactions' => function($query){ 
                    $query->where('amt_debt', '>', 0)
                        ->orderBy('transaction_date', 'desc'); 
                    },
            ]);
        }

        return view('transfers.show', compact('transfer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function edit(Transfer $transfer)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transfer $transfer)
    {
        return abort(404);
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transfer $transfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transfer $transfer)
    {
        if($transfer->is_receive){
            $transfer->transactions()->delete();
        }
        
        $transfer->transferTransactions()->delete();
        $transfer->delete();

        return back()->with('success', "Transfer Deleted");
    }
}
