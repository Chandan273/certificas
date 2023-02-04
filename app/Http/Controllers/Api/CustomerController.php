<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function createCustomer(Request $request){
        return CustomerService::createCustomer($request);
    }

    public function allCustomers(Request $request){
        return CustomerService::allCustomers($request);
    }

    public function updateCustomer(Request $request){
        return CustomerService::updateCustomer($request);
    }

    public function destroyCustomer(Request $request){
        return CustomerService::destroyCustomer($request);
    }
}
