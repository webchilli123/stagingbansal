<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryVoucher extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $dates = ['voucher_date'];

    
    public static function voucherNumber()
    {
        return self::doesntExist()
         ? 1001
         : self::max('voucher_number') + 1;
    }

    public function attendances()
    {
        return $this->hasMany(EmployeeAttendance::class, 'salary_voucher_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    
}
