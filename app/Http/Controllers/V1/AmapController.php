<?php

namespace App\Http\Controllers\V1;


use App\Services\AmapService;
use Illuminate\Http\Request;
use Torann\GeoIP\Facades\GeoIP;

/**
 * Class AmapController
 * @author WXiangQian <175023117@qq.com>
 * @package App\Http\Controllers\V1
 */
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
     *      summary="根据经纬度获取地理位置-高德地图-逆地理编码",
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

    /**
     * @SWG\Post(
     *      path="/amap/geo",
     *      tags={"public"},
     *      operationId="amap_geo",
     *      summary="根据地址获取经纬度类的信息-高德地图-地理编码",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          in="body",
     *          name="data",
     *          description="",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="address",description="填写结构化地址信息:省份＋城市＋区县＋城镇＋乡村＋街道＋门牌号码",type="string"),
     *              @SWG\Property(property="city",description="查询城市，可选：城市中文、中文全拼、citycode、adcode",type="string"),
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
     *                  @SWG\Property(property="count", type="string", description="返回结果数目"),
     *                  @SWG\Property(property="info", type="string", description="当status为0时，info会返回具体错误原因，否则返回“OK”"),
     *                  @SWG\Property(property="geocodes", type="array",description="地理编码信息列表",
     *                      @SWG\Items(type="object",
     *                          @SWG\Property(property="formatted_address", type="string",description="省＋市＋区县＋城镇＋乡村＋街道＋门牌"),
     *                          @SWG\Property(property="province", type="string",description="地址所在的省份名"),
     *                          @SWG\Property(property="city", type="string",description="地址所在的城市名"),
     *                          @SWG\Property(property="citycode", type="string",description="城市编码"),
     *                          @SWG\Property(property="district", type="string",description="地址所在的区"),
     *                          @SWG\Property(property="location", type="string",description="坐标点-经度，纬度"),
     *                      ),
     *                  ),
     *              ),
     *          )
     *      ),
     * )
     */
    public function getLonAndLat(Request $request)
    {
        $address = $request->input('address','');
        $city = $request->input('city','');

        $position = AmapService::geo($address, $city);

        return $this->responseData($position);
    }

}