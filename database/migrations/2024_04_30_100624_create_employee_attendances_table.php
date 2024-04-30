<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttendancesTable extends Migration
{
    public function up()
    {
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->bigInteger('employee_id')->nullable(false);
            $table->date('attendance_date')->nullable(false);
            $table->time('start_time')->nullable(false);
            $table->time('end_time')->nullable(false);
            $table->integer('salary_voucher_id')->nullable();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_attendances');
    }
}
