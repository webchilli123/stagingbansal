<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // all permissions data
        
        $names = [
            'items',
            'categories',
            'cities',
            'parties',
            'permissions',
            'roles',
            'users',
            'orders',
            'transfers',
            'vouchers',
            'transports',
            'employees',
            'designations',
            'processes',
        ];

        foreach($names as $name){
                
            DB::table('permissions')->insert([
                ['name' => "browse_{$name}"],
                ['name' => "create_{$name}"],
                ['name' => "edit_{$name}"],
                ['name' => "delete_{$name}"],
            ]);
        }
    }
}
