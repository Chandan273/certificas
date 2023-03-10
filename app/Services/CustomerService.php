<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;

class CustomerService
{
    /**
     * Display the customer listing resource.
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

                    $response = ['success' => false, 'message' => 'No data found', 'statusCode' => 400];
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
                $response = ['success' => true, 'data' => $customers, 'totalCount' => $totalCount, 'statusCode' => 200];
            } else {
                $response = ['success' => false, 'data' => [], 'totalCount' => $totalCount, 'message' => 'No Data Found!!','statusCode' => 404 ];
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e, 'statuscode' => 500];
        }

        return $response;
    }

    /**
     * Store a customer resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public static function store(Request $request)
    {
        try {
            $customer = Customer::create(array_merge($request->all(), [
                'tenant_id' => auth()->user()->id,
            ]));

            $response = ['success' => true, 'message' => 'Customer Created Succesfully!', 'statusCode' => 200];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e, 'statusCode' => 400];
        }

        return $response;
    }

    /**
     * Update the customer resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public static function update(Request $request)
    {
        try {
            $customer = Customer::where('id', $request->id)->first();
            $customer->update($request->all());

            $response = ['success' => true, 'message' => 'Customer Updated Succesfully!', 'statusCode' => 200];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
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

            $response = ['success' => true, 'message' => 'User has been deleted successfully!', 'statusCode' => 200];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }
}

?>
