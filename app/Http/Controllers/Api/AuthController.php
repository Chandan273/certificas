<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\UserService;
use App\Services\RegisterService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * login a user resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request )
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData,[
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'false', 'error'=>$validator->errors()], 400);
        }

        return AuthService::login($request);
    }

    /**
     * logout user resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response 
     */
    public function logout(Request $request)
    {
        return AuthService::logout($request);
    }

    /**
     * User info resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response 
     */    
    public function userInfo()
    {
        $user = auth()->user();
        return response()->json(["status" => "success", "user" => $user],200);
    }

    /**
     * forgot password resource from storage.
     *
     * * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function forgotPassword(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData,[
            'email' => 'email|required',
        ]);

        if ($validator->fails()) {

            return response()->json(['success' => false, 'error'=>$validator->errors()], 422);
        }

        return UserService::forgotPassword($request);
    }

    /**
     * Reset password resource from storage.
     *
     * * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
    public function resetPassword(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|string|min:8|same:confirm_password',
                'confirm_password' => 'required'
            ]
        );
        if ($validator->fails()) {

            return response()->json(['success' => false, 'error'=> $validator->errors()], 422);
        }

        return UserService::resetPassword($request);
    }

    /**
     * update user profile resource from storage.
     *
     * * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */  
    public function updateProfile(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return UserService::updateProfile($request);
    }

    /**
     * Reset profile user password resource from storage.
     *
     * * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */     
    public function profilePassword(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => 'required',
                'password' => 'required|string|min:8|same:confirm_password',
                'confirm_password' => 'required'
            ]
        );
        if ($validator->fails()) {

            return response()->json(['success' => false, 'error'=> $validator->errors()], 422);
        }

        return UserService::profilePassword($request);
    }
}
