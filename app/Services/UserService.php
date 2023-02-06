<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class UserService
{
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

    public static function resetPassword(Request $request)
    {
        try {
            $email = base64_decode($request->get('email'));
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->update([
                    'password' => \Hash::make($request->password),
                ]);

                $response = [
                    'message' => 'Password Updated Sucessfully!!',
                    'success' => true,
                ];
                $statusCode = 200;
            } else {
                $response = [
                    'message' => 'User Not Found!!',
                    'success' => false,
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

                    $response = response()->json(
                        [
                            'success' => true,
                            'user' => $user,
                            'message' =>
                                'SuperAdmin Profile has been updated successfully!',
                        ],
                        200
                    );
                }
            } else {
                $tenant = Tenant::with('user')
                    ->where('user_id', $request->user_id)
                    ->first();
                if ($tenant) {
                    $user = User::where('id', $request->user_id)->first();
                    $user->username = $request->username;
                    $user->email = $request->email;
                    $user->save();

                    $tenant = Tenant::where(
                        'user_id',
                        $request->user_id
                    )->first();
                    $tenant->name = $request->name;
                    $tenant->save();

                    $response = response()->json(
                        [
                            'success' => true,
                            'user' => $user,
                            'message' =>
                                'Tenant Profile has been updated successfully',
                        ],
                        200
                    );
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e];
            $statusCode = 400;
        }

        return $response;
    }

    public static function profilePassword(Request $request)
    {
        try {
            if (
                auth()
                    ->user()
                    ->hasRole('superadmin')
            ) {
                $user = User::where('id', $request->user_id)->first();
                if ($user) {
                    if (Hash::check($request->current_password, $user->password)) {
                        $user->password = Hash::make($request->password);
                        $user->save();
                        $response = [
                            'success' => true,
                            'message' => 'Superadmin profile password has been Updated Sucessfully!!',
                        ];
                    }
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'User Not Found!!',
                    ];
                    $statusCode = 401;
                }
            } else {
                $tenant = Tenant::with('user')
                    ->where('user_id', $request->user_id)
                    ->first();
                if ($tenant) {
                    $user = User::where('id', $request->user_id)->first();

                    if ($user) {
                        if (Hash::check($request->current_password, $user->password)) {
                            $user->password = Hash::make($request->password);
                            $user->save();
                            $response = [
                                'success' => true,
                                'message' =>
                                    'Profile password has been Updated Sucessfully!!',
                            ];
                        }
                    } else {
                        $response = [
                            'success' => false,
                            'message' => 'User Not Found!!',
                        ];
                        $statusCode = 401;
                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e];
            $statusCode = 400;
        }

        return $response;
    }
}
