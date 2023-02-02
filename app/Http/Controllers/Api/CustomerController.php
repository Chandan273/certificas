<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function createCustomer(CustomerService $customerService, Request $request){
        return $customerService->createCustomer($request);
    }
}
