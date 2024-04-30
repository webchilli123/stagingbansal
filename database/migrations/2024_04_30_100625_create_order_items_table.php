<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->bigInteger('order_id')->nullable(false);
            $table->bigInteger('item_id')->nullable(false);
            $table->decimal('ordered_quantity')->nullable(false);
            $table->decimal('received_quantity')->nullable(false)->default('0.00');
            $table->decimal('rate')->nullable(false);
            $table->decimal('total_price')->nullable(false);
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
