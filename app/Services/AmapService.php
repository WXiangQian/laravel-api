<?php

namespace App\Services;


class AmapService
{
    // Key 是高德Web服务 Key。详细可以参考上方的请求参数说明。

    /**
     * 将详细的结构化地址转换为高德经纬度坐标-高德地图-地理编码
     * @param string $address 填写结构化地址信息:省份＋城市＋区县＋城镇＋乡村＋街道＋门牌号码
     * @param string $city 查询城市，可选：城市中文、中文全拼、citycode、adcode
     * @return array
     */
    public static function geo($address, $city)
    {
        $key = config('app.amap_key');
        /**
         * url:https://restapi.amap.com/v3/geocode/geo?address=北京市朝阳区阜通东大街6号&output=XML&key=<用户的key>
         * output（XML/JSON）用于指定返回数据的格式
         */
        $url = "https://restapi.amap.com/v3/geocode/geo?output=JSON&key={$key}&address={$address}&city={$city}";

        // 执行请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);

        return $result;
    }

    /**
     * 根据经纬度获取地理位置-高德地图-逆地理编码
     * @param string $lon 经度
     * @param string $lat 纬度
     * @return array
     */
    public static function regeo($lon, $lat)
    {
        $key = config('app.amap_key');
        // location(116.310003,39.991957) 是所需要转换的坐标点经纬度，经度在前，纬度在后，经纬度间以“,”分割
        $location = $lon . "," . $lat;
        /**
         * url:https://restapi.amap.com/v3/geocode/regeo?output=xml&location=116.310003,39.991957&key=<用户的key>&radius=1000&extensions=all
         * radius（1000）为返回的附近POI的范围，单位：米
         * extensions 参数默认取值是 base，也就是返回基本地址信息
         * extensions 参数取值为 all 时会返回基本地址信息、附近 POI 内容、道路信息以及道路交叉口信息。
         * output（XML/JSON）用于指定返回数据的格式
         */
        $url = "https://restapi.amap.com/v3/geocode/regeo?output=JSON&location={$location}&key={$key}&radius=1000&extensions=base";

        // 执行请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);

        return $result;
    }

}
