<?php
namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpeningStockTable extends Migration
{
    public function up()
    {
        Schema::create('opening_stock', function (Blueprint $table) {
            $table->integer('id')->nullable(false);
            $table->string('party_name')->nullable();
            $table->string('item_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('quantity_acc')->nullable();
            $table->integer('amount')->nullable();
            $table->string('amount_acc')->nullable();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('opening_stock');
    }
}
