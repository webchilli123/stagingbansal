<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'start_time',
        'end_time',
        'salary_voucher_id',
    ];

    protected $dates = ['attendance_date'];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];


    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
    public function salaryVoucher()
    {
        return $this->belongsTo(SalaryVoucher::class, 'salary_voucher_id');
    }

}
