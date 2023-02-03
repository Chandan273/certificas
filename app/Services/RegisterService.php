<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;

class RegisterService {
    public static function register($input) {

        $tenant = Tenant::create([
            'name' => $input->tenant['name'],
            'paid_untill' => $input->tenant['paid_untill'],
            'archived' => Carbon::now()
        ]);

        $user = User::create([
            'name' => $input->user['name'],
            'tenantId' => $tenant->id,
            'username' => $input->user['name'],
            'email' => $input->user['email'],
            'language' => $input->user['language'],
            'password' => \Hash::make($input->user['password']),
        ]);

        $token = $user->createToken("Token")->accessToken;
        return response()->json(["token"=>$token, "user"=>$user],200);
    }
}
