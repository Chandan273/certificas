<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\RegisterService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request )
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData,[
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'false', 'error'=>$validator->errors()], 422);
        }

        return AuthService::login($request);
    }

    public function logout(Request $request)
    {
        return AuthService::logout($request);
    }


    public function register(Request $request )
    {
        return RegisterService::register($request);
    }

    public function userInfo()
    {
        $user = auth()->user();
        return response()->json(["status" => "success", "user" => $user],200);
    }

}
