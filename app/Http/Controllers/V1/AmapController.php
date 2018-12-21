<?php

namespace App\Http\Controllers\V1;


use App\Services\AmapService;
use Illuminate\Http\Request;
use Torann\GeoIP\Facades\GeoIP;

class AmapController extends BaseController
{
    /**
     * @SWG\Get(
     *      path="/location",
     *      tags={"public"},
     *      operationId="location",
     *      summary="获取用户地理位置",
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
     *                  @SWG\Property(property="ip", type="string", description="ip"),
     *                  @SWG\Property(property="iso_code", type="string", description="标准国家码"),
     *                  @SWG\Property(property="country", type="string", description="国家"),
     *                  @SWG\Property(property="city", type="string", description="城市"),
     *                  @SWG\Property(property="postal_code", type="string", description="邮政编码"),
     *                  @SWG\Property(property="lat", type="string", description="纬度"),
     *                  @SWG\Property(property="lon", type="string", description="精度"),
     *                  @SWG\Property(property="timezone", type="string", description="时区"),
     *              ),
     *          )
     *      ),
     * )
     */
    public function getLocation(Request $request)
    {
        $ip = $request->ip();
        $location = GeoIP::getLocation($ip)->toArray();
        return $this->responseData($location);
    }

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