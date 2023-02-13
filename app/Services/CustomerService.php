<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

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

                    $response = ['success' => false, 'message' => 'No data found', 'stausCode' => 400];
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
                $response = ['success' => true, 'data' => $customers, 'totalCount' => $totalCount, 'stausCode' => 200];
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

            //$customer->assignRole("customer");

            // $mailData = ["username"=>$user->username, "password"=>$password];

            // Mail::send('mails.password', $mailData, function ($message) use ($user) {
            //     $message->from('admin@certificas.com', 'Certificas');
            //     $message->to($user->email)->subject('Account created');
            // });

            $response = ['success' => true, 'message' => 'Customer Created Succesfully!', 'customer' => $customer, 'statusCode' => 200];
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

            $response = ['success' => true, 'message' => 'Customer Updated Succesfully!', 'customer' => $customer, 'statusCode' => 200];
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
