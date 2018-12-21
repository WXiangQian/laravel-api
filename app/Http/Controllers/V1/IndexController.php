<?php

namespace App\Http\Controllers\V1;

use App\Transformers\UserInfoTransformer;

class IndexController extends BaseController
{

    /**
     * @SWG\Get(
     *      path="/home",
     *      tags={"public"},
     *      operationId="home",
     *      summary="获取首页数据",
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
    public function index()
    {
        $data = [
            'id' => 1,
            'name' => 'test测试',
        ];
        return $this->responseData(UserInfoTransformer::transform($data));
    }

}