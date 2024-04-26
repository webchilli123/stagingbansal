<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    const INV = 'inv';
    const ACC = 'acc';

    const DIRECT_SALE = 'direct sale';
    const DIRECT_PURCHASE = 'direct purchase';

    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_date');
    }

    public function Getparty()
    {
        return $this->hasOne(Party::class, 'id','creditor_id');
    }

    public function GetpartyAgain()
    {
        return $this->hasOne(Party::class, 'id','debitor_id');
    }
    public static function transactionNumber()
    {
        return self::doesntExist()
         ? 1
         : self::max('transaction_number') + 1;
    }

    public static function createStockVoucher($request){
        
        $item = Item::find($request->item_id);
        $transactionNumber = Transaction::transactionNumber();
        $price = $request->amount * $request->rate;
        $description = "Item : {$item->name}, Rate : {$request->rate}, Price: {$price}, - {$request->narration}";

        Transaction::create([
            'transaction_date' => $request->transaction_date,
            'type' => 'inv',
            'debitor_id' => $request->dr_party,
            'creditor_id' => $request->cr_party,
            'stock_debt' => $request->amount,
            'narration' => $description,
            'transaction_number' => $transactionNumber,
        ]);

        return Transaction::create([
                'transaction_date' => $request->transaction_date,
                'type' => 'inv',
                'debitor_id' => $request->cr_party,
                'creditor_id' => $request->dr_party,
                'stock_credit' => $request->amount,
                'narration' => $description,
                'transaction_number' => $transactionNumber,
            ]);

    }

    public function updateStock($request){
          
        if ($request->dr_party == Party::SELF_STORE) {
            $quantity = $request->amount;
            $type = self::DIRECT_PURCHASE;
        } else {
           $quantity = "-{$request->amount}"; 
           $type = self::DIRECT_SALE;
        }
        
        TransferTransaction::create([
            'item_id' => $request->item_id,
            'party_id' => Party::SELF_STORE,
            'quantity' => $quantity,
            'rate' => $request->rate,
            'type'=> $type,
            'transaction_id'=> $this->id,
            'created_at' => $request->transaction_date
        ]);

    }

}
