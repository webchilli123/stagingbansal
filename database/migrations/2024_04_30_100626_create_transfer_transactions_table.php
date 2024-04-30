<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transfer_transactions', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->bigInteger('item_id')->nullable(false);
            $table->bigInteger('party_id')->nullable(false);
            $table->decimal('quantity')->nullable(false);
            $table->string('type')->nullable(false);
            $table->decimal('waste')->nullable();
            $table->decimal('rate')->nullable(false);
            $table->bigInteger('material_id')->nullable();
            $table->bigInteger('worker_id')->nullable();
            $table->bigInteger('transport_id')->nullable();
            $table->string('bilty_number')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->date('transport_date')->nullable();
            $table->bigInteger('transfer_id')->nullable();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('transaction_id')->nullable();
            $table->date('created_at')->nullable(false);
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transfer_transactions');
    }
}
