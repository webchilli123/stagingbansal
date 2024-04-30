<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateLogsTable extends Migration
{
    public function up()
    {
        Schema::create('update_logs', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->bigInteger('order_id')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('module')->nullable();
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('update_logs');
    }
}
