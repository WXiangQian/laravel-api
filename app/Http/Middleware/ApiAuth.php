<?php

namespace App\Http\Middleware;


use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use JWTAuth;

class ApiAuth
{
    public function handle($request, Closure $next)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {  //获取到用户数据，并赋值给$user
                return response()->json([
                    'errcode' => 1004,
                    'errmsg' => 'user not found'

                ], 404);
            }
            return $next($request);

        } catch (TokenExpiredException $e) {

            return response()->json([
                'errcode' => 1003,
                'errmsg' => 'token 过期', //token已过期
            ]);

        } catch (TokenInvalidException $e) {

            return response()->json([
                'errcode' => 1002,
                'errmsg' => 'token 无效',  //token无效
            ]);

        } catch (JWTException $e) {

            return response()->json([
                'errcode' => 1001,
                'errmsg' => '缺少token', //token为空
            ]);

        }
    }

}