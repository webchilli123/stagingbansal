<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;

    protected $table = 'opening_stock';
    protected $guarded = [];
    public $timestamps = false;


    public function party()
    {
        return $this->hasOne(Party::class, 'id','party_name');
    }

    public function getItem()
    {
        return $this->hasOne(Item::class, 'id','item_name');
    }
}