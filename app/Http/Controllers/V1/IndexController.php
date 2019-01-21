<?php

namespace App\Http\Controllers\V1;

use App\Transformers\UserInfoTransformer;
use Illuminate\Http\Request;
use Wythe\Logistics\Logistics;

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

    public function express(Request $request)
    {
        $code = $request->input('code', '');
        if (!$code) {
            return $this->responseError('请输入要查询的快递单号');
        }
        $logistics = new Logistics();

        $data = $logistics->query($code, 'kuaidi100');
        return $this->responseData($data);
    }
}