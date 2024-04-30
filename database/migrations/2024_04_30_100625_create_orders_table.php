<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->bigInteger('order_number')->nullable(false);
            $table->date('order_date')->nullable(false);
            $table->date('due_date')->nullable(false);
            $table->string('type')->nullable(false);
            $table->boolean('entry_type')->nullable(false)->default('1');
            $table->string('status')->nullable(false);
            $table->bigInteger('party_id')->nullable(false);
            $table->text('narration')->nullable();
            $table->text('wa_narration')->nullable(false);
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
