<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = []; 

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'item_id');
    }

    public function transferTransactions()
    {
        return $this->hasOne(TransferTransaction::class, 'item_id','id')->select('quantity');
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

}
