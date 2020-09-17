<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        LogicException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * @param \Illuminate\Http\Request $request
     * @param Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     * User: WXiangQian
     */
    public function render($request, Exception $e)
    {
        $code = 5000;
        /*逻辑代码异常*/
        if ($e instanceof LogicException) {
            $code = $e->getMessage();
            $message = config('apicode.' . $code);
        } elseif ($e instanceof ValidationException) { // 验证异常
//            $errors = $e->errors();
//            if (is_array($errors) && !empty($errors)) {
//                $code = 5001;
//                foreach ($errors as $k => $v) {
//                    $data[] = $v[0];
//                }
//            }
            $errorinfo = array_slice($e->errors(), 0, 1, false);
            $msg = array_column($errorinfo, 0);
            $message = $msg[0];
        } else {
            //prod 环境，统一返回内部错误
            $errFile = $e->getFile();
            $errLine = $e->getLine();
            $errMsg = $e->getMessage();
            $data = [
                'errorMsg' => $errMsg,
                'errLine' => $errLine,
                'errFile' => $errFile
            ];
        }

        return response()->json([
            'code' => $code,
            'message' => $message ?? '内部服务器错误',
            'data' => $data ?? [],
        ], 200);

        return $this->responseMessage($code);
    }
}
