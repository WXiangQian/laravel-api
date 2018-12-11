<?php

namespace App\Http\Controllers\V1;


use App\Models\User;
use App\Transformers\UserInfoTransformer;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class ArticleController extends BaseController
{

    /**
     * @SWG\Get(
     *      path="/news/list",
     *      tags={"public"},
     *      operationId="home",
     *      summary="获取文章列表",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="code", type="string",description="状态码"),
     *              @SWG\Property(property="message", type="string",description="提示信息"),
     *              @SWG\Property(property="data", type="object",
     *
     *              ),
     *          )
     *      ),
     * )
     */
    public function getNewsList()
    {

    }


}