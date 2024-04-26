<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('voucher_number')->unique();
            $table->date('voucher_date');
            $table->foreignId('employee_id');
            $table->string('amount', 50);
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_vouchers');
    }
}
