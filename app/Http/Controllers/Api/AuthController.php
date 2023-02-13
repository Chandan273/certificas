<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\UserService;
use App\Services\RegisterService;
use App\Http\Controllers\Controller;
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

        return response()->json(AuthService::login($request));
    }

    /**
     * logout user resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response 
     */
    public function logout(Request $request)
    {
        return response()->json(AuthService::logout($request));
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

        return response()->json(UserService::forgotPassword($request));
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

        return response()->json(UserService::resetPassword($request));
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

        return response()->json(UserService::updateProfile($request));
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

        return response()->json(UserService::profilePassword($request));
    }
}
