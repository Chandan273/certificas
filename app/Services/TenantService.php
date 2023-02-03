<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Certificate_layout;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class TenantService
{
    /**
     * Display a listing for all users.
     */
    public static function allTenants(Request $request)
    {
        try {
            $totalCount = 0;
            $tenants = User::with('tenant')->role('company');

            if($request->has('requireTotalCount')){
                $totalCount = $tenants->count();
            }

            if($request->has('skip')){
                $tenants->skip($request->skip)->take($request->take);
            }

            if($request->has('sort')){
                $sort = json_decode($request->sort,1);
                if(count($sort)){
                    $tenants->orderBy($sort[0]['selector'], $sort[0]['desc'] ? 'DESC' : 'ASC');
                }

            }

            $tenants = $tenants->get();

            if ( $tenants) {
                return $respone = response()->json(
                    ['success' => true, 'data' => $tenants, 'totalCount' => $totalCount],
                    200
                );
            } else {
                return $respone = response()->json(
                    ['success' => false, "data" => [],'totalCount' => $totalCount, 'message' => 'No Data Found!!'],
                    401
                );
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $respone = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function createTenant(Request $request)
    {
        try {

            $password = Str::random(8);

            $tenant = Tenant::create([
                'name' => $request->name,
                'paid_untill' => date('Y-m-d', strtotime($request->paid_untill))
            ]);

            $user = User::create([
                'tenant_id' => $tenant->id,
                'username' => $request->username,
                'email' => $request->email,
                'password' => \Hash::make($password),
            ]);  
            
            $certificate_layout = Certificate_layout::create([
                'tenant_id' => $tenant->id
            ]);

            $role = Role::findOrCreate('company');
            $user->assignRole($role);

            $mailData = ["username"=>$user->username, "password"=>$password];

            Mail::send('mails.password', $mailData, function ($message) use ($user) {
                $message->from('admin@certificas.com', 'Certificas');
                $message->to($user->email)->subject('Account created');
            });

            return $respone = response()->json(
                ['success' => true,  'user' => $user, 'message' => 'Tenant has been added successfully'],
                200
            );

        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $respone = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }
    }

    /**
     * Show a company user in storage.
     */
    public function showTenants($id)
    {
        try {
            $user = User::find($id);

            return $respone = response()->json(
                ['success' => true, 'user' => $user],
                200
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $respone = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }
    }

    /**
     * Update Company User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function updateTenant(Request $request)
    {
        try {
            $tenant = Tenant::where('id', $request->id)->first();
            $tenant->name = $request->name;
            $tenant->paid_untill = $request->paid_untill;
            $tenant->save();

            $user = User::where('tenant_id', $request->id)->first();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->save();

            return $respone = response()->json(
                ['success' => true,  'user' => $user, 'message' => 'Tenant has been updated successfully'],
                200
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $respone = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }
    }

    /**
     * Delete teanat customer from storage.
     */
    public static function destroyTenant(Request $request)
    {
        try {

            Tenant::where('id', $request->id)->delete();
            User::where('tenant_id', $request->id)->delete();
            Certificate_layout::where('tenant_id', $request->id)->delete();

            return $respone = response()->json(
                [
                    'success' => true,
                    'message' => 'User has been deleted successfully!',
                ],
                200
            );

        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $respone = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }
    }
}
