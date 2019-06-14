<?php

namespace App\Http\Middleware;

use Closure;

define('START', microtime(true));

class OverTimeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 开始
//        Log::info($_SERVER['REQUEST_URI'].'开始'.START.'微秒');
        $response = $next($request);

        return $response;
    }


    public function terminate($request, $response)
    {
        //结束
        $end = microtime(true);
//        Log::info($_SERVER['REQUEST_URI'].'结束'.$end.'微秒');
        $diff = ($end-START) * 1000;

//        if ($diff) {
        if ($diff >= 1500) {
            write_log('接口超时记录日志','info',storage_path('logs/overtime.log'),"接口{".$_SERVER['REQUEST_URI']."}从请求开始到结束相差{$diff}毫秒");
        }
    }
}
