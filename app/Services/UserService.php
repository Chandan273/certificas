<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService
{
    /**
     * forgot password resource from storage.
     *
     * * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */    
    public static function forgotPassword(Request $request)
    {
        try {
            $user = User::where('email', $request->only('email'))->first();

            if ($user) {
                $token = Str::random(64);

                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);

                $link =
                    URL::to('/') .
                    '/reset-password?email=' .
                    base64_encode($user->email);

                $mailData = [
                    'name' => $user->username,
                    'resetPassword' => $link,
                ];

                Mail::send('mails.forgot-password', $mailData, function (
                    $message
                ) use ($request) {
                    $message->to($request->email);
                    $message->subject('Reset Password');
                });

                $response = [
                    'success' => true,
                    'message' => 'Reset link has been sent to registered mail',
                ];
                $statusCode = 200;
            } else {
                $response = [
                    'success' => false,
                    'message' => 'No account found with this email',
                ];
                $statusCode = 401;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e];
            $statusCode = 400;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Reset password resource from storage.
     *
     * * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public static function resetPassword(Request $request)
    {
        try {
            $email = base64_decode($request->get('email'));
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->update([
                    'password' => \Hash::make($request->password),
                ]);

                $response = ['success' => true, 'message' => 'Password Updated Sucessfully!!', 'statusCode' => 200];
            } else {
                
                $response = ['success' => false, 'message' => 'User Not Found!!', 'statusCode' => 401];
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }

    /**
     * update user profile resource from storage.
     *
     * * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */  
    public static function updateProfile(Request $request)
    {
        try {
            if (
                auth()
                    ->user()
                    ->hasRole('superadmin')
            ) {
                $user = User::where('id', $request->user_id)->first();
                if ($user) {
                    $user->username = $request->username;
                    $user->email = $request->email;
                    $user->save();

                    $response = ['success' => true, 'user' => $user, 'message' => 'Profile has been updated successfully!', 'statusCode' => 200];
                }
            } else {
                $user = User::where('tenant_id', $request->user_id)->first();
                if ($user) {

                    $tenant = Tenant::where('id', $request->user_id)->first();
                    $tenant->name = $request->name;
                    $tenant->save();

                    $user->username = $request->username;
                    $user->email = $request->email;
                    $user->save();

                    $response = ['success' => true, 'user' => $user, 'tenant' => $tenant, 'message' => 'Profile has been updated successfully', 'statusCode' => 200];
                }
            }
        }catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }

    /**
     * Reset profile user password resource from storage.
     *
     * * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */  
    public static function profilePassword(Request $request)
    {
        try {
            
                $user = User::where('id', $request->user_id)->first();
                if (Hash::check($request->current_password, $user->password)) {
                    $user->password = Hash::make($request->password);
                    $user->save();

                    return $response = ['success' => true,'message' => 'Profile password has been Updated Sucessfully!', 'statusCode' => 200];
                } else {

                    return $response = ['success' => false, 'message' => 'current password is incorrect!', 'statusCode' => 401];
                }
        
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return ['success' => false, 'message' => $e->getMessage()];
        }

    }
}
