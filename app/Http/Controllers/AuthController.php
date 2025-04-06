<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'mobile' => 'required|string|max:255',
            ]
        );

        $userData = $request->all();
        $userService = new UserService();
        $user = $userService->store($userData);

        $verificationCode = rand(1000, 9999);

        $user->verification_code = $verificationCode;
        $user->save();

        $data = [
            'verification_code' => $verificationCode,
            'message' => 'ثبت نام با موفقیت انجام شد!',
        ];

        return response()->json([$data, 200]);
    }

    public function confirmVerificationCode(Request $request)
    {
        $verificationCode = $request->get('verification_code');
        $mobile = $request->get('mobile');

        $user = User::query()->where('mobile', $mobile)->first();

        if ($verificationCode == $user->verification_code) {
            $user->mobile_verified_at = now();
            $user->save();
            $data = [
                'token' => $user->createToken('user-registered')->plainTextToken,
                'message' => 'ثبت نام با موفقیت انجام شد!',
            ];
        } else {
            $data = [
                'message' => 'کد ارسالی نادرست است!',
            ];
        }

        return response()->json([$data, 400]);
    }

    public function login(Request $request)
    {
    }
}
