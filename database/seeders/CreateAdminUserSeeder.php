<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tenant = Tenant::create([
            'name' => 'Super Admin',
            'paid_untill' => '2050-03-25',
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'username' => 'superadmin',
            'email' => 'admin@gmail.com',
            'language' => 'English',
            'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'superadmin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
