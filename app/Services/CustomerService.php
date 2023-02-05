<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CustomerService{

    public static function createCustomer(Request $request){
        try{

            $tenant = Tenant::where('id',auth()->user()->id)->first();
            $customer = Customer::create([
                "tenant_id" => $tenant->id,
                "number" => $request->number,
                "name" => $request->name,
                "contact" => $request->contact,
                "organisation_number" => $request->organisation_number,
                "email" => $request->email,
                "www" => $request->www,
                "phone" => $request->phone,
                "address" => $request->address,
                "zip" => $request->zip,
                "city" => $request->city,
                "country" => $request->country,
                "info" => null
            ]);

            $response = response()->json(
                ['success' => true, 'message' => "Customer Created Succesfully!"],
                200
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }

        return $response;

    }

    public static function allCustomers(Request $request){
        try{
            $totalCount = 0;
            $customers = Customer::where('tenant_id', auth()->user()->id);

            if($request->has('requireTotalCount')){
                $totalCount = $customers->count();
            }

            if($request->has('skip')){
                $customers->skip($request->skip)->take($request->take);
            }

            if($request->has('sort')){
                $sort = json_decode($request->sort,1);
                if(count($sort)){
                    $customers->orderBy($sort[0]['selector'], $sort[0]['desc'] ? 'DESC' : 'ASC');
                }

            }

            $customers = $customers->get();

            if ( $customers) {
                $response = response()->json(
                    ['success' => true, 'data' => $customers, 'totalCount' => $totalCount],
                    200
                );
            } else {
                $response = response()->json(
                    ['success' => false, "data" => [],'totalCount' => $totalCount, 'message' => 'No Data Found!!'],
                    401
                );
            }
        }catch(Exception $e){
            Log::error($e->getMessage());

            $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }

        return $response;

    }

    /**
     * Update Company User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function updateCustomer(Request $request)
    {
        try {
            $tenant = Tenant::where('id', $request->tenant_id)->first();

            if($tenant){
                $customer = Customer::where('id', $request->id)->first();
                $customer->number = $request->number;
                $customer->name = $request->name;
                $customer->contact = $request->contact;
                $customer->organisation_number = $request->organisation_number;
                $customer->email = $request->email;
                $customer->www = $request->www;
                $customer->phone = $request->phone;
                $customer->address = $request->address;    
                $customer->zip = $request->zip;
                $customer->city = $request->city;   
                $customer->country = $request->country;
                $customer->info = null;
                $customer->save();

                $response = response()->json(
                    ['success' => true, 'message' => 'Customer has been updated successfully'],
                    200
                );
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }

        //return $response;

    }

    /**
     * Delete teanat customer from storage.
     */
    public static function destroyCustomer(Request $request)
    {
        try {

            Customer::where('id', $request->id)->delete();

            $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Customer has been deleted successfully!',
                ],
                200
            );

        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }

        return $response;
    }    

}


?>
