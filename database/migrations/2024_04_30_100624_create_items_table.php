<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigInteger('id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('user_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('master_id')->nullable();
            $table->string('part_number')->nullable();
            $table->string('group')->nullable();
            $table->string('item_alias')->nullable();
            $table->string('category')->nullable();
            $table->string('hsn_code')->nullable();
            $table->decimal('tax_rate')->nullable();
            $table->integer('rate')->nullable(false);
            $table->bigInteger('current_quantity')->nullable(false);
            $table->decimal('quantity')->nullable(false);
            $table->datetime('created_at');
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
