<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name'=>'company', 'guard_name'=> 'api', 'created_at' => date('Y-m-d H:i:s')],
            // ['name'=>'customer', 'guard_name'=> 'api', 'created_at' => date('Y-m-d H:i:s')],
        ];

        Role::insert($data); // Eloquent approach
    }
}
