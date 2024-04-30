<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportsTable extends Migration
{
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('address')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transports');
    }
}
