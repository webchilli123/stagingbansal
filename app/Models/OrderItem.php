<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $appends = [
        'rate_formatted',
        'total_price_formatted'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
   


    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function getRateFormattedAttribute(){
        return number_format((float)$this->rate, 2, '.', '');
    }

    public function getTotalPriceFormattedAttribute(){
        return number_format((float)$this->total_price, 2, '.', '');
    }


}
