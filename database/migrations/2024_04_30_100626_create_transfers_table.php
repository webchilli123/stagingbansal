<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->integer('transfer_number')->nullable(false);
            $table->date('transfer_date')->nullable(false);
            $table->boolean('is_receive')->nullable(false);
            $table->bigInteger('sender_id')->nullable(false);
            $table->bigInteger('receiver_id')->nullable(false);
            $table->bigInteger('process_id')->nullable(false);
            $table->bigInteger('order_id')->nullable();
            $table->text('narration')->nullable();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}
