<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CustomerService
{
    /**
     * Store a newly customer resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public static function store(Request $request)
    {
        try {
            $customer = Customer::create([
                'tenant_id' => auth()->user()->id,
                'number' => $request->number,
                'name' => $request->name,
                'contact' => $request->contact,
                'organisation_number' => $request->organisation_number,
                'email' => $request->email,
                'www' => $request->www,
                'phone' => $request->phone,
                'address' => $request->address,
                'zip' => $request->zip,
                'city' => $request->city,
                'country' => $request->country,
                'info' => null,
            ]);
            $customer->save();

            return $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Customer Created Succesfully!',
                    'customer' => $customer,
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

    /**
     * Display the specified customer resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */      
    public static function index(Request $request)
    {
        try {
            $totalCount = 0;
            if (
                auth()
                    ->user()
                    ->hasRole('superadmin')
            ) {
                $customers = Customer::all();
            } else {
                $tenant = User::where('id', auth()->user()->id)->first();
                if ($tenant) {
                    $customers = Customer::with('tenant')->where(
                        'tenant_id',
                        $tenant->id
                    );
                } else {
                    return $response = response()->json(
                        ['success' => false, 'message' => 'No data found'],
                        400
                    );
                }
            }

            if ($request->has('requireTotalCount')) {
                $totalCount = $customers->count();
            }

            if ($request->has('skip')) {
                $customers->skip($request->skip)->take($request->take);
            }

            if ($request->has('sort')) {
                $sort = json_decode($request->sort, 1);
                if (count($sort)) {
                    $customers->orderBy(
                        $sort[0]['selector'],
                        $sort[0]['desc'] ? 'DESC' : 'ASC'
                    );
                }
            }

            $customers = $customers->get();

            if ($customers) {
                return $response = response()->json(
                    [
                        'success' => true,
                        'data' => $customers,
                        'totalCount' => $totalCount,
                    ],
                    200
                );
            } else {
                return $response = response()->json(
                    [
                        'success' => false,
                        'data' => [],
                        'totalCount' => $totalCount,
                        'message' => 'No Data Found!!',
                    ],
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
     * Update the specified customer resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public static function update(Request $request)
    {
        try {
            $customer = Customer::where('id', $request->id)->first();
            $customer->tenant_id = $request->tenant_id;
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

            return $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Customer Updated Succesfully!',
                    'customer' => $customer,
                ],
                200
            );
            // }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }
    }

    /**
     * Delete record with the customer id in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */     
    public static function destroy(Request $request)
    {
        try {
            $customer = Customer::where('id', $request->id)->first();
            $customer->delete();
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

?>
