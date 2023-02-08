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
     * Display the specified Tenant resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request)
    {
        try {
            $totalCount = 0;
            $tenants = User::with('tenant')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'company');
            });

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
                return $response = response()->json(
                    ['success' => true, 'data' => $tenants, 'totalCount' => $totalCount],
                    200
                );
            } else {
                return $response = response()->json(
                    ['success' => false, "data" => [],'totalCount' => $totalCount, 'message' => 'No Data Found!!'],
                    401
                );
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $response = response()->json(
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
    public static function store(Request $request)
    {
        try {

            $password = Str::random(8);

            $tenant = Tenant::create([
                'name' => $request->name,
                'paid_untill' => date('y-m-d', strtotime($request->paid_untill))
            ]);

            $user = User::create([
                'tenant_id' => $tenant->id,
                'username' => $request->username,
                'email' => $request->email,
                'password' => \Hash::make($password),
            ]);

            $certificate_layout = Certificate_layout::create([
                'tenant_id' => $tenant->id,
            ]);

            $user->assignRole('company');

            $mailData = ["username"=>$user->username, "password"=>$password];

            Mail::send('mails.password', $mailData, function ($message) use ($user) {
                $message->from('admin@certificas.com', 'Certificas');
                $message->to($user->email)->subject('Account created');
            });

            return $response = response()->json(
                ['success' => true, 'user' => $user, 'message' => 'Tenant has been added successfully'],
                200
            );

        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $response = response()->json(
                ['success' => false, 'message' => $e],
                500
            );
        }
    }

    /**
     * Display a company user in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function show($id)
    {
        try {
            $user = User::find($id);

            return $response = response()->json(
                ['success' => true, 'user' => $user],
                200
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $response = response()->json(
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
    public static function update(Request $request)
    {
        try {

            $tenant = Tenant::where('id', $request->id)->first();
            $tenant->name = $request->name;
            $tenant->paid_untill = date('y-m-d', strtotime($request->paid_untill));
            $tenant->save();

            $user = User::where('tenant_id', $tenant->id)->first();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->save();

            return $response = response()->json(
                ['success' => true, 'user' => $user, 'message' => 'Tenant has been updated successfully'],
                200
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }
    }

    /**
     * Delete teanat customer from storage.
     */
    public static function destroy(Request $request)
    {
        try {

            $user = User::where('tenant_id', $request->id)->first();
            $tenant = Tenant::where('id', $request->id)->first();
            $certificate_layout = Certificate_layout::where('tenant_id', $request->id)->delete();
            $tenant->delete();
            $user->delete();
            return $response = response()->json(
                [
                    'success' => true,
                    'message' => 'User has been deleted successfully!',
                ],
                200
            );

        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }
    }
}
