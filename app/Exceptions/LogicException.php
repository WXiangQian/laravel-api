<?php
/**
 * Created by PhpStorm.
 * User: wxiangqian
 * Date: 2020-07-12
 * Time: 22:29
 */

namespace App\Exceptions;

use Exception;

class LogicException extends Exception
{
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {

    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     */
    public function render($request, Exception $e)
    {

    }
}