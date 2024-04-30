<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->bigInteger('transaction_number')->nullable(false);
            $table->date('transaction_date')->nullable(false);
            $table->string('type')->nullable(false);
            $table->text('narration')->nullable();
            $table->bigInteger('debitor_id')->nullable(false);
            $table->bigInteger('creditor_id')->nullable();
            $table->float('stock_debt')->nullable(false);
            $table->float('stock_credit')->nullable(false);
            $table->float('amt_debt')->nullable(false);
            $table->float('amt_credit')->nullable(false);
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('transfer_id')->nullable();
            $table->boolean('is_paid')->nullable(false);
            $table->string('wa_narration')->nullable();
            $table->string('gst_amount')->nullable();
            $table->string('extra_charges')->nullable();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
