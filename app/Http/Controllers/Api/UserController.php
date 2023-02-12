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
     * Display a listing of the Tenants.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return TenantService::index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'paid_untill' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return TenantService::store($request);
    }

    /**
     * Display the specified Tenant resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TenantService $tenantService, $id)
    {
        $showUser = $tenantService->show($request);
        return $showUser;
    }

    /**
     * Update the specified Tenant resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'paid_untill' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return TenantService::update($request);
    }

    /**
     * Delete record with the Tenant id in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TenantService $tenantService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return TenantService::destroy($request);
    }

}
