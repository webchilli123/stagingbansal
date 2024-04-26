<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];
    protected $table = 'transfer_transactions';

    protected $dates = [
        'created_at',
        'transport_date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function Getparty()
    {
        return $this->hasOne(Party::class, 'id','worker_id');
    }

    public function GetopStock()
    {
        return $this->hasOne(Jobs::class, 'item_name','item_id');
    }

    // public function transport()
    // {
    //     return $this->hasOne(Transport::class, 'id','transport_id');
    // }

    public function GetpartyMet()
    {
        return $this->hasOne(Item::class, 'id','material_id');
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class, 'transport_id')->select('id', 'name', 'phone_number');
    }


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->select('id', 'order_number','narration');
    }

    public function orderDate()
    {
        return $this->hasOne(Order::class, 'id','order_id');
    }

    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');//->select('id','transaction_number')
    }

    public function transfer_transaction()
    {
        return $this->belongsTo(Item::class,'material_id');//->select('id','transaction_number')
    }

    public function transfer_transactionWorker()
    {
        return $this->belongsTo(Party::class,'worker_id');//->select('id','transaction_number')
    }
}
//ALTER TABLE `transfer_transactions` ADD `return` INT NULL DEFAULT NULL AFTER `created_at`;
