<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id')->select('id', 'name', 'address', 'phone');
    }
}


