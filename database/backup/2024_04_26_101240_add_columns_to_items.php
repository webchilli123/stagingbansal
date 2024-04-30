<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            //
            Schema::table('items', function (Blueprint $table) {
                //
                $table->string('user_id')->nullable()->after('name');
                $table->string('company_name')->nullable()->after('user_id');
                $table->string('master_id')->nullable()->after('company_name');
                $table->string('part_number')->nullable()->after('master_id');
                $table->string('group')->nullable()->after('part_number');
                $table->string('item_alias')->nullable()->after('group');
                $table->string('category')->nullable()->after('item_alias');
                $table->string('hsn_code')->nullable()->after('category');
                $table->decimal('tax_rate')->nullable()->after('hsn_code');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
