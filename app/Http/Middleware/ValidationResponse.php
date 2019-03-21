<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ValidationResponse
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


        /**
         * @var $response \Illuminate\Http\Response
         */
        $response =  $next($request);


        if ($response instanceof JsonResponse && isset($response->exception) && $response->exception instanceof ValidationException) {

           return response()->json([
               'code' => 1,
               'message' => current(json_decode($response->content(), true)['errors'])[0],
           ], 422);
        }

        return $response ;
    }
}
