<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignationsTable extends Migration
{
    public function up()
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('designations');
    }
}
