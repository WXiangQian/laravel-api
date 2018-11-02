<?php

namespace App\Http\Controllers;

use Swagger\Annotations as SWG;

class SwaggerController extends Controller
{

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
