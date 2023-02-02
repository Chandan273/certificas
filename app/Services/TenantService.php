<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tenant;
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
    static public function allTenants(Request $request)
    {
        try {
            $totalCount = 0;
            $tenants = Tenant::with('user');

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
            // ->orderBy('id', 'ASC')->get();
            //->paginate(5);

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
    public function createTenant($input)
    {
        try {

            $password = Str::random(8);

            $user = User::create([
                'username' => $input->username,
                'email' => $input->email,
                'password' => \Hash::make($password),
            ]);

            $tenant = Tenant::create([
                'user_id' => $user->id,
                'name' => $input->company_name,
                'paid_untill' => $input->paid_untill
            ]);

            $user->assignRole('company');

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
    public function updateTenant($input)
    {
        try {
            $user = User::where('id', $input->id)->first();
            $user->username = $input->username;
            $user->email = $input->email;
            $user->save();

            $tenant = Tenant::where('user_id', $input->id)->first();
            $tenant->name = $input->company_name;
            $tenant->save();

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
    public function destroyTenant($input)
    {
        try {

            $user = User::where('id', $input->id)->first();
            Tenant::where('user_id', $input->id)->delete();
            $user->delete();
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
