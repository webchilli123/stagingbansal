<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateLogs extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const SALE_MODULE = 'sale module';

    public function user(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
