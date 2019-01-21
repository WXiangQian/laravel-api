<?php

namespace App\Http\Controllers\V1;

use App\Transformers\ExpressTransformer;
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

    /**
     * @SWG\Post(
     *      path="/express",
     *      tags={"public"},
     *      operationId="express",
     *      summary="查询快递信息",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          in="body",
     *          name="data",
     *          description="",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="code",description="快递单号",type="string"),
     *          )
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="code", type="string",description="状态码"),
     *              @SWG\Property(property="message", type="string",description="提示信息"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="status", type="string", description="201单号不存在或者已经过期；200表示请求成功"),
     *                  @SWG\Property(property="message", type="string", description="当status为201时，info会返回具体错误原因，否则返回“OK”"),
     *                  @SWG\Property(property="logistics_company", type="string", description="具体哪家快递"),
     *                  @SWG\Property(property="data", type="array",description="快递详情",
     *                      @SWG\Items(type="object",
     *                          @SWG\Property(property="time", type="string",description="时间"),
     *                          @SWG\Property(property="description", type="string",description="快递进度"),
     *                      ),
     *                  ),
     *              ),
     *          )
     *      ),
     * )
     */
    public function express(Request $request)
    {
        $code = $request->input('code', '');
        if (!$code) {
            return $this->responseError('请输入要查询的快递单号');
        }
        $logistics = new Logistics();

        $data = $logistics->query($code, 'kuaidi100');
        return $this->responseData(ExpressTransformer::transform($data));
    }
}