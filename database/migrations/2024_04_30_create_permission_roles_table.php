<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionRoleTable extends Migration
{
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->bigInteger('permission_id')->nullable(false);
            $table->bigInteger('role_id')->nullable(false);
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('permission_role');
    }
}
