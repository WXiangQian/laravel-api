<?php

namespace App\Services;


use App\Exceptions\LogicException;

class ShortUrlService extends BasicService
{
    /**
     * 调用新浪接口将长链接转为短链接
     * @param  array|string $urlLong 长链接，支持多个转换（需要先执行urlencode)
     * @throws LogicException
     * @return array
     */
    public static function getSinaShortUrl($urlLong)
    {
        // $source 申请应用的AppKey
        $source = config('app.sina_key');
        // 参数检查
        if (!$source || !$urlLong) {
            throw new LogicException(5001);
        }
        // 参数处理，字符串转为数组
        if (!is_array($urlLong)) {
            $urlLong = array($urlLong);
        }
        // 拼接url_long参数请求格式
        $url_param = array_map(function ($value) {
            return '&url_long=' . urlencode($value);
        }, $urlLong);
        $url_param = implode('', $url_param);
        /**
         * 新浪生成短链接接口
         * 返回结果是JSON格式:http://api.t.sina.com.cn/short_url/shorten.json
         * 返回结果是XML格式:http://api.t.sina.com.cn/short_url/shorten.xml
         */
        $api = 'http://api.t.sina.com.cn/short_url/shorten.json';
        // 请求url
        $request_url = sprintf($api . '?source=%s%s', $source, $url_param);
        $result = array();
        // 执行请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        $data = curl_exec($ch);

        curl_close($ch);
        $result = json_decode($data, true);
        return $result;
    }


}
