<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('staff_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->unsignedInteger('rate');
            $table->foreignId('designation_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_type_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designations');
        Schema::dropIfExists('staff_types');
        Schema::dropIfExists('employees');
    }
}
