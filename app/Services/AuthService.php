<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthService {
    /**
     * login a user resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function login(Request $request) {
        try{

            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if(auth()->attempt($data)){
                $token = auth()->user()->createToken("Token")->accessToken;
                $user = auth()->user();
                $tenant = Tenant::where('id', auth()->user()->id)->first();
                $role = $user->roles()->first()->name;

                $response = response()->json(["status" => true, "access_token"=> $token, 'user' => $user, 'role'=>$role, 'tenant'=> $tenant], 200);
            }else{
                $response = response()->json(['status'=> false, 'error' => 'Incorrect Password', 'message' => "Please check Login details"], 401);
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());

            $response = response()->json(["success" => false, "message"=>$e], 400);
        }

        return $response;

    }

    /**
     * logout user resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response 
     */    
    public static function logout(Request $request)
    {
        try{
            $token = $request->user()->token();
            $token->revoke();

            return response()->json(['status'=> 'true', 'message' => 'You have been successfully logged out!'], 200);
        }
        catch(Exception $e){
            Log::error($e->getMessage());

            return $response =  response()->json(["success" => false, "message"=>$e], 400);
        }
    }
}
