<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthService {
    public static function login($input) {

        try{
            $data = [
                'email' => $input->email,
                'password' => $input->password
            ];

            if(auth()->attempt($data)){
                $token = auth()->user()->createToken("Token")->accessToken;
                $user = auth()->user();
                $role = $user->roles()->first()->name;

                return response()->json(["status" => true, "access_token"=> $token, 'user' => $user, 'role'=>$role], 200);
            }else{
                return response()->json(['status'=> false, 'error' => 'UnAuthorised Access', 'message' => "Please check Login details"], 401);
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());

            return $response =  response()->json(["success" => false, "message"=>$e], 400);
        }

    }

    public static function logout ($request)
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
