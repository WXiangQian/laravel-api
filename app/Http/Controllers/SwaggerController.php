<?php

namespace App\Http\Controllers;

use Swagger\Annotations as SWG;

class SwaggerController extends Controller
{
    /**
     * @SWG\Swagger(
     *     schemes={"http","https"},
     *     host="api.host.com",
     *     basePath="/",
     *     @SWG\Info(
     *         version="1.0.0",
     *         title="This is my website cool API",
     *         description="Api description...",
     *         termsOfService="",
     *     ),
     * )
     */

    public function getJSON()
    {
        // 正式环境(production)访问swagger文档时返回空
        if (config('app.env') == 'production') {
            return response()->json([], 200);
        }

        $swagger = \Swagger\scan(app_path('/'));

        return response()->json($swagger, 200);
    }
}
