<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttandancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attandances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->date('attendance_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('salary_voucher_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_attandances');
    }
}
