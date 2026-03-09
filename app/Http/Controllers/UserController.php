<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Helper\JWTToken;

class UserController extends Controller
{
    public function UserLogin(Request $request): JsonResponse
    {
        $request->validate([
            'UserEmail' => ['required', 'email'],
        ]);

        try {
            $UserEmail = trim((string) $request->input('UserEmail'));
            $OTP = (string) rand(100000, 999999);

            // Send mail
            Mail::to($UserEmail)->send(new OTPMail(['code' => $OTP]));

            // Save/Update user OTP
            User::updateOrCreate(
                ['email' => $UserEmail],
                [
                    'email' => $UserEmail,
                    'otp' => $OTP,
                    'otp_expires_at' => now()->addMinutes(10),
                ]
            );


            return response()->json([
                'msg' => 'success',
                'data' => 'A 6 digit OTP has been sent to your email address',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'msg' => 'fail',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function VerifyLogin(Request $request): JsonResponse
    {
        $request->validate([
            'UserEmail' => ['required', 'email'],
            'OTP'       => ['required', 'digits:6'],
        ]);

        try {
            $UserEmail = trim((string) $request->input('UserEmail'));
            $OTP       = trim((string) $request->input('OTP'));

            $user = User::where('email', $UserEmail)->first();

            if (!$user) {
                return response()->json([
                    'msg' => 'fail',
                    'data' => 'User not found',
                ], 404);
            }

            if (!$user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
                return response()->json([
                    'msg' => 'fail',
                    'data' => 'OTP expired. Please request a new OTP.',
                ], 401);
            }

            if (!$user->otp || (string) $user->otp !== (string) $OTP) {
                return response()->json([
                    'msg' => 'fail',
                    'data' => 'Invalid OTP',
                ], 401);
            }

            // OTP verified
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
                'email_verified_at' => now(),
            ]);

            //  Create token
            $token = JWTToken::CreateToken($UserEmail, $user->id);
            $cookieMinutes = 60 * 24 * 30;

            return response()
                ->json([
                    'msg' => 'success',
                    'data' => 'Login successful',
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                    ],
                ], 200)
                ->cookie('token', $token, $cookieMinutes);

        } catch (Exception $e) {
            return response()->json([
                'msg' => 'fail',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function UserLogout(): JsonResponse
    {
        return response()
            ->json([
                'msg' => 'success',
                'data' => 'Logged out',
            ], 200)
            ->cookie('token', '', -1);
    }
}
