<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Store a newly customer resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'number' => 'required',
            'name' => 'required',
            'contact' => 'required',
            'organisation_number' => 'required',
            'email' => 'required|email|unique:customers',
            'www' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'zip' => 'required',
            'city' => 'required',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return response()->json(CustomerService::store($request));
    }

    /**
     * Display the specified customer resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public function index(Request $request)
    {
        return response()->json(CustomerService::index($request));
    }

    /**
     * Update the specified customer resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'tenant_id'=> 'required',
            'number' => 'required',
            'name' => 'required',
            'contact' => 'required',
            'organisation_number' => 'required',
            'email' => 'required',
            'www' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'zip' => 'required',
            'city' => 'required',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return response()->json(CustomerService::update($request));
    }
    
    /**
     * Delete record with the customer id in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public function destroy(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }
        return response()->json(CustomerService::destroy($request));
    }

}
