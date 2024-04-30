<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->integer('rate')->nullable(false);
            $table->bigInteger('designation_id')->nullable(false);
            $table->bigInteger('staff_type_id')->nullable(false);
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
