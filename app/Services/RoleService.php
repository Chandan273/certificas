<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService {

    public function allRoles(){
        try{
            $roles = Role::all();

            return $response =  response()->json(["roles"=>$roles, "success" => true],200);
        }
        catch(Exception $e){
            Log::error($e->getMessage());

            return $response =  response()->json(["message"=>$e, "success" => false],400);
        }
    }

    public function createRoles($input){
        try{
            $role = Role::create([
                'name' => $input->name
            ]);

            $role->syncPermissions($input->permission);

            return $response =  response()->json(["role"=>$role, "success" => true],200);
        }
        catch(Exception $e){
            Log::error($e->getMessage());

            return $response =  response()->json(["message"=>$e, "success" => false],400);
        }
    }

    public function showPermissions($roleId){
        try{
            $role = Role::find($id);
            $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                ->where("role_has_permissions.role_id",$id)
                ->get();

            return $response =  response()->json(["rolePermissions"=>$rolePermissions, "success" => true],200);
        }
        catch(Exception $e){
            Log::error($e->getMessage());

            return $response =  response()->json(["message"=>$e, "success" => false],400);
        }
    }

    public function updateRoles($input){
        try{
            //return $response =  response()->json(["role"=>$role, "success" => true],200);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
            //return $response =  response()->json(["message"=>$e, "success" => false],400);
        }
    }

}

?>
