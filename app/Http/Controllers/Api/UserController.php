<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TenantService;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the Users.
     *
     * @return \Illuminate\Http\Response
     */
    public function allTenants(Request $request)
    {
        $allTenants = TenantService::allTenants($request);
        return $allTenants;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createTenant(TenantService $tenantService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $createTenant = $tenantService->createTenant($request);
        return $createTenant;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TenantService $tenantService, $id)
    {
        $showUser = $tenantService->showTenants($request);
        return $showUser;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTenants(TenantService $tenantService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'username' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $updateCustomer = $tenantService->updateTenant($request);
        return $updateCustomer;
    }

    /**
     * Delete record with the tenant id in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyTenant(TenantService $tenantService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $destroyTenant = $tenantService->destroyTenant($request);
        return $destroyTenant;
    }

}
