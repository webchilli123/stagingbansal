<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            //
            $table->id();
            $table->integer('order_number');
            $table->unsignedBigInteger('item_number');
            $table->unsignedBigInteger('total_quantity');
            $table->unsignedBigInteger('sent_quantity');
            $table->decimal('rate');
            $table->decimal('total_price');
            $table->string('narration');
            $table->string('whats_app_narration');
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
        Schema::table('bills', function (Blueprint $table) {
            //
        });
    }
}
