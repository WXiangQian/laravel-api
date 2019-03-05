<?php

namespace App\Http\Controllers;

use Swagger\Annotations as SWG;

/**
 * Class SwaggerController
 * @author WXiangQian <175023117@qq.com>
 * @package App\Http\Controllers
 */
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
