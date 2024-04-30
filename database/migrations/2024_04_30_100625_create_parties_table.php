<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartiesTable extends Migration
{
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->integer('opening_balance')->nullable(false);
            $table->string('address')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('url')->nullable();
            $table->string('tin_number')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->text('note')->nullable();
            $table->string('type')->nullable(false);
            $table->bigInteger('user_id')->nullable(false);
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('parties');
    }
}
