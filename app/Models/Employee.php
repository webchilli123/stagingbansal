<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_number',
        'address',
        'designation_id',
        'staff_type_id',
        'rate',
    ];

    public $timestamps = false;


    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function staffType()
    {
        return $this->belongsTo(StaffType::class, 'staff_type_id');
    }
   
}
