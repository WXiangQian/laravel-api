<?php

namespace App\Http\Controllers\V1;


use App\Services\AmapService;
use Illuminate\Http\Request;

class AmapController extends BaseController
{

    /**
     * @SWG\Post(
     *      path="/amap/regeo",
     *      tags={"public"},
     *      operationId="amap_regeo",
     *      summary="根据经纬度获取地理位置-高德地图",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          in="body",
     *          name="data",
     *          description="",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="lon",description="经度",type="string"),
     *              @SWG\Property(property="lat",description="纬度",type="string"),
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
     *                  @SWG\Property(property="status", type="string", description="0表示请求失败；1表示请求成功"),
     *                  @SWG\Property(property="info", type="string", description="当status为0时，info会返回具体错误原因，否则返回“OK”"),
     *                  @SWG\Property(property="regeocodes", type="array",description="逆地理编码列表",
     *                      @SWG\Items(type="object",
     *                          @SWG\Property(property="formatted_address", type="string",description="省＋市＋区县＋城镇＋乡村＋街道＋门牌"),
     *                          @SWG\Property(property="addressComponent", type="array",description="地址元素列表",
     *                              @SWG\Items(type="object",
     *
     *                              ),
     *                          ),
     *                      ),
     *                  ),
     *              ),
     *          )
     *      ),
     * )
     */
    public function getPosition(Request $request)
    {
        $lon = $request->input('lon','');
        $lat = $request->input('lat','');
        $position = AmapService::regeo($lon, $lat);

        return $this->responseData($position);
    }

}