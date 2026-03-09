<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use App\Helper\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            /**
             *  If Authorization header missing, try cookies
             * (some setups store token in cookie)
             */
            if (!$request->header('Authorization')) {
                $cookieToken =
                    $request->cookie('token') ??
                    $request->cookie('access_token') ??
                    $request->cookie('jwt') ??
                    null;

                if ($cookieToken) {
                    $request->headers->set('Authorization', 'Bearer ' . $cookieToken);
                }
            }

            //  Read & validate JWT token
            $tokenData = JWTToken::ReadToken($request);

            if ($tokenData === 'unauthorized' || !$tokenData) {
                return ResponseHelper::Out('fail', 'Unauthorized', 401);
            }

            //  Robust payload keys (handle different token payload names)
            $userId =
                $tokenData->userid ??
                $tokenData->user_id ??
                $tokenData->id ??
                null;

            $email =
                $tokenData->userEmail ??
                $tokenData->email ??
                null;

            if (!$userId) {
                return ResponseHelper::Out('fail', 'Unauthorized (missing user id)', 401);
            }

            //  merge so controller can use: $request->input('user_id')
            $request->merge([
                'user_id' => (int) $userId,
                'email'   => (string) ($email ?? ''),
            ]);

            return $next($request);

        } catch (\Throwable $e) {
            return ResponseHelper::Out('fail', 'Unauthorized', 401);
        }
    }
}
