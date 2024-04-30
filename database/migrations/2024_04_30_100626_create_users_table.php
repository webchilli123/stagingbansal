<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->string('username')->nullable(false);
            $table->string('password')->nullable(false);
            $table->bigInteger('role_id')->nullable(false);
            $table->string('remember_token')->nullable();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
