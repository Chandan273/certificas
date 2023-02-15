<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Certificate_layout;
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

        // Adding New User

        $tenant = Tenant::create([
            'name' => 'superadmin',
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'language' => 'English',
            'password' => bcrypt('123456')
        ]);

        $certificate_layout = Certificate_layout::create([
            'tenant_id' => $tenant->id,
        ]);

        $role = Role::create(['name' => 'superadmin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);

        // Adding Permissions
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
 
         //Add New Role
 
         $rolesData = [
             ['name'=>'company', 'guard_name'=> 'api', 'created_at' => date('Y-m-d H:i:s')],
         ];
 
         Role::insert($rolesData);

    }
}
