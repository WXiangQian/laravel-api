<?php

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller ;
use App\Http\Controllers\SwaggerController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class BaseController extends SwaggerController
{
    use AuthenticatesUsers ;

    /**
     * @SWG\Swagger(
     *   host="",
     *   basePath="/api/v1",
     *   @SWG\Info(
     *     title="app",
     *     version="1.0.0"
     *   ),
     * @SWG\SecurityScheme(
     *   securityDefinition="api_key",
     *   type="apiKey",
     *   in="header",
     *   description = "认证token  Bearer+空格+token",
     *   name="Authorization"
     * ),
     * )
     */
    public function __construct()
    {

    }

    public function responseData($data, $code = 0, $message = 'success', $status = 200)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $status, [], JSON_UNESCAPED_UNICODE);
    }

    public function responseSuccess($message = 'success')
    {
        return $this->responseMessage(0, $message);
    }

    public function responseFailed($message = 'failed')
    {
        return $this->responseMessage(2, $message);
    }

    public function responseError($message = 'error')
    {
        return $this->responseMessage(1, $message);
    }

    public function responseMessage($code, $message, $status = 200)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
        ], $status);
    }

    public function responseNotFound($message = 'not found')
    {
        return response()->json([
            'code' => 404,
            'message' => $message,
        ], 404);
    }
}