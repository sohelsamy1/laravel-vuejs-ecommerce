<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
}
