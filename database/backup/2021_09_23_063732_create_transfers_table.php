<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('transfer_number');
            $table->date('transfer_date');
            $table->boolean('is_receive');
            $table->foreignId('sender_id');
            $table->foreignId('receiver_id');
            $table->foreignId('process_id');
            $table->foreignId('order_id')->nullable();
            $table->text('narration')->nullable();
        });


        Schema::create('transfer_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->foreignId('party_id');
            $table->decimal('quantity', 10, 2);
            $table->string('type');
            $table->decimal('waste', 10, 2)->nullable();
            $table->decimal('rate', 10, 4);
            $table->foreignId('material_id')->nullable();
            $table->foreignId('worker_id')->nullable();
            $table->foreignId('transport_id')->nullable();
            $table->string('bilty_number')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('transport_date')->nullable();
            $table->foreignId('transfer_id')->nullable();
            $table->foreignId('order_id')->nullable();
            $table->date('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
        Schema::dropIfExists('transfer_items');
        Schema::dropIfExists('transfer_transactions');
    }
}
