<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LoginService;
use App\Services\RegisterService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(LoginService $loginService, Request $request )
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData,[
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'false', 'error'=>$validator->errors()], 422);
        }

        return $loginService->login($request);
    }

    public function logout(LoginService $loginService, Request $request)
    {
        return $loginService->logout($request);
    }


    public function register(RegisterService $registerService, Request $request )
    {
        return $registerService->register($request);
    }

    public function userInfo()
    {
        $user = auth()->user();
        return response()->json(["status" => "success", "user" => $user],200);
    }

}
