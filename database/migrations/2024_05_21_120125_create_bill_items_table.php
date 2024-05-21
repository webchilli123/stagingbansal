<?php

use App\Models\Bill;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Bill::class);
            $table->string('order_id')->nullable();
            $table->string('item_id')->nullable();
            $table->string('item')->nullable();
            $table->string('sent_quantity')->nullable();
            $table->string('rate')->nullable();
            $table->string('price')->nullable();
            $table->string('order_quantity')->nullable();
            $table->string('order_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_items');
    }
}
