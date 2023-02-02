<?php

namespace App\Services;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CustomerService{
    public function createCustomer($input){
        try{
            $customer = Customer::create([
                "number" => $input->number,
                "name" => $input->name,
                "contact" => $input->contact,
                "organisation_number" => $input->organisation_number,
                "email" => $input->email,
                "www" => $input->www,
                "phone" => $input->phone,
                "address" => $input->address,
                "zip" => $input->zip,
                "city" => $input->city,
                "country" => $input->country,
                "info" => json_encode($input->info)
            ]);
            $customer->save();

            return $respone = response()->json(
                ['success' => true, 'message' => "Customer Created Succesfully!", 'customer' => $customer],
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


?>
