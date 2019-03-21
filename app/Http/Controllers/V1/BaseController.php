<?php

namespace App\Http\Controllers\V1;


use App\Http\Controllers\SwaggerController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * Class BaseController
 * @author WXiangQian <175023117@qq.com>
 * @package App\Http\Controllers\V1
 */
class BaseController extends SwaggerController
{
//    use AuthenticatesUsers ;

    /**
     * @SWG\Swagger(
     *   host="",
     *   basePath="/api/v1",
     *   @SWG\Info(
     *     title="laravel-api",
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

    /**
     * @param $data
     * @param int $code
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseData($data, $code = 0, $message = 'success', $status = 200)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $status, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($message = 'success', $status = 200)
    {
        return $this->responseMessage(0, $message, $status);
    }

    /**
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseFailed($message = 'failed', $status = 500)
    {
        return $this->responseMessage(1, $message, $status);
    }

    /**
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError($message = 'error', $status = 400)
    {
        return $this->responseMessage(1, $message, $status);
    }

    /**
     * @param $code
     * @param $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseMessage($code, $message, $status = 200)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
        ], $status);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseNotFound($message = 'not found')
    {
        return response()->json([
            'code' => 404,
            'message' => $message,
        ], 404);
    }
}