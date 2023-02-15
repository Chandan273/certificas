<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
           'add',
           'edit',
           'import',
           'create',
           'send'
        ];

        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }


        $rolesData = [
            ['name'=>'company', 'guard_name'=> 'api', 'created_at' => date('Y-m-d H:i:s')],
        ];

        Role::insert($rolesData);

    }
}
