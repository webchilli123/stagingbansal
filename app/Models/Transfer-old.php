<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $table = 'transfers';


    protected $dates = ['transfer_date'];


    public function sender()
    {
        return $this->belongsTo(Party::class, 'sender_id')->select('id', 'name');
    }

    public function receiver()
    {
        return $this->belongsTo(Party::class, 'receiver_id')->select('id', 'name');
    }


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public static function transferNumber()
    {
        return self::doesntExist()
         ? 1001
         : self::max('transfer_number') + 1;
    }

    public function transferTransactions()
    {
        return $this->hasMany(TransferTransaction::class, 'transfer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transfer_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }
    
    
    public function updateStockOnTransfer($request){
        
        foreach ($request->send_items_id as $i => $send_item_id) {

            // decrease stock of sender 
            TransferTransaction::create([
                'item_id' => $send_item_id,
                'party_id' => Party::SELF_STORE,
                'quantity' => "-{$request->send_quantities[$i]}",
                'type' => 'transfer',
                'transfer_id' => $this->id,
                'created_at' => $request->transfer_date,
                'material_id' => $request->sender_id[$i],
                'worker_id' => $this->sender_id,
            ]);

            // increase stock of receiver 
            TransferTransaction::create([
                'item_id' => $send_item_id,
                'party_id' => $request->receiver_id,
                'quantity' => $request->send_quantities[$i],
                'type' => 'receive',
                'transfer_id' => $this->id,
                'created_at' => $request->transfer_date
            ]);
        }
    }

   
    // update stock on receive transfer
    public function updateStockOnReceive($request){

        foreach($request->items_id as $i => $item_id){
            
            // increase receive item stock
            TransferTransaction::create([
                'item_id' => $request->receive_item_id[$i],
                'party_id' => Party::SELF_STORE,
                'quantity' => $request->receive_quantities[$i],
                'type' => 'receive',
                'waste' => $request->wastes[$i],
                'transfer_id' => $this->id,
                'created_at' => $request->transfer_date,
                'material_id' => $item_id,
                'worker_id' => $this->sender_id,
            ]);

            // descrease used item stock
            TransferTransaction::create([
                'item_id' => $item_id,
                'party_id' => $this->sender_id,
                'material_id' => $request->receive_item_id[$i],
                'worker_id' => Party::SELF_STORE,
                'quantity' => "-{$request->receive_quantities[$i]}",
                'type' => 'used',
                'waste' => $request->wastes[$i],
                'rate' => $request->rates[$i],
                'transfer_id' => $this->id,
                'created_at' => $request->transfer_date
            ]);
        }
    }


    // create account voucher on transfer receive
    public function createReceiveAccountVoucher($request)
    {
        $request->filled('narration')
        ? $description = "Transfer No. {$this->transfer_number} - {$request->narration}"
        : $description = "Transfer No. {$this->transfer_number}";

        $transactionNumber = Transaction::transactionNumber();
        
        Transaction::create([
            'transaction_date' => $request->transfer_date,
            'type' => 'acc',
            'debitor_id' => Party::STOCK_TRANSFER,
            'creditor_id' => $this->sender_id,
            'amt_debt' => $request->payment_amount,
            'narration' => $description,
            'transaction_number' => $transactionNumber,
            'transfer_id' => $this->id
        ]);

        Transaction::create([
            'transaction_date' => $request->transfer_date,
            'type' => 'acc',
            'debitor_id' => $this->sender_id,
            'creditor_id' => Party::STOCK_TRANSFER,
            'amt_credit' => $request->payment_amount,
            'narration' => $description,
            'transaction_number' => $transactionNumber,
            'transfer_id' => $this->id
        ]);
    }

}
