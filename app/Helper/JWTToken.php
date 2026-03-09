<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use Illuminate\Http\Request;

class JWTToken
{
    public static function CreateToken($userEmail, $userID)
    {
        $key = env("JWT_KEY");
        if (!$key) return null;

        $payload = [
            "iss" => "laravel-token",
            "iat" => time(),
            "exp" => time() + 24 * 60 * 60,
            "userEmail" => $userEmail,
            "userid" => $userID
        ];

        return JWT::encode($payload, $key, "HS256");
    }

    public static function ReadToken(Request $request): object|string
    {
        try {

            $token = $request->header('Authorization');

            if (!$token && $request->cookie('token')) {
                $token = 'Bearer ' . $request->cookie('token');
            }

            if (!$token) {
                return "unauthorized";
            }

            if (str_starts_with($token, 'Bearer ')) {
                $token = substr($token, 7);
            }

            $key = env("JWT_KEY");
            if (!$key) return "unauthorized";

            return JWT::decode($token, new Key($key, "HS256"));

        } catch (Exception $e) {
            return "unauthorized";
        }
    }
}
