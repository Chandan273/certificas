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

        $user = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'language' => 'English',
            'password' => bcrypt('123456')
        ]);

        $tenant = Tenant::create([
            'user_id' => $user->id,
            'name' => 'Super Admin',
            'paid_untill' => '2023-03-25',
        ]);

        $role = Role::create(['name' => 'superadmin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
