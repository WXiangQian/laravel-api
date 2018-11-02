<?php

namespace App\Services;


class ShortUrlService
{
    /**
     * 调用新浪接口将长链接转为短链接
     * @param  string        $source    申请应用的AppKey
     * @param  array|string  $urlLong  长链接，支持多个转换（需要先执行urlencode)
     * @return array
     */
    public static function getSinaShortUrl($source, $urlLong)
    {
        // 参数检查
        if(empty($source) || !$urlLong){
            return false;
        }
        // 参数处理，字符串转为数组
        if(!is_array($urlLong)){
            $urlLong = array($urlLong);
        }
        // 拼接url_long参数请求格式
        $url_param = array_map(function($value){
            return '&url_long='.urlencode($value);
        }, $urlLong);
        $url_param = implode('', $url_param);
        // 新浪生成短链接接口
        $api = 'http://api.t.sina.com.cn/short_url/shorten.json';
        // 请求url
        $request_url = sprintf($api.'?source=%s%s', $source, $url_param);
        $result = array();
        // 执行请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        $data = curl_exec($ch);
        if($error=curl_errno($ch)){
            return false;
        }
        curl_close($ch);
        $result = json_decode($data, true);
        return $result;
    }


}
