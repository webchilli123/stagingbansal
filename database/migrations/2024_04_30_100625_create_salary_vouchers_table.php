<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryVouchersTable extends Migration
{
    public function up()
    {
        Schema::create('salary_vouchers', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->integer('voucher_number')->nullable(false);
            $table->date('voucher_date')->nullable(false);
            $table->integer('employee_id')->nullable(false);
            $table->string('amount')->nullable(false);
            $table->text('description')->nullable();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_vouchers');
    }
}
