<?php

namespace App\Services;

use App;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;

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

            App::setLocale($request->lang);
            session()->put('locale', $request->lang);

            if(auth()->attempt($data)){
                $token = auth()->user()->createToken("Token")->accessToken;
                $user = auth()->user();
                $tenant = Tenant::where('id', auth()->user()->id)->first();
                $role = $user->roles()->first()->name;

                $response = ["success" => true, "accessToken"=> $token, 'user' => $user, 'role'=>$role, 'tenant'=> $tenant, 'statusCode'=> 200];
            }else{
                $response = ['success'=> false, 'error' => Lang::get('messages.loginErr'), 'message' => "Please check Login details", 'statusCode'=> 401];
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());

            $response = ["success" => false, "message"=>$e->getMessage(), 'statusCode'=> 500];
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

            $response = ['success'=> true, 'message' => 'You have been successfully logged out!', 'statusCode'=> 200];
        }
        catch(Exception $e){
            Log::error($e->getMessage());

            $response = ["success" => false, "message"=>$e->getMessage(), 'statusCode'=> 500];
        }

        return $response;
    }
}
