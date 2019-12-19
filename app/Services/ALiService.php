<?php
/**
 * 阿里api
 * User: 175023117@qq.com
 * Date: 2019-11-21 15:03
 */

namespace App\Services;


use saf\Request\V20180919 as saf;

class ALiService extends BasicService
{

    public static function run($json_data)
    {
        include_once dirname(__DIR__).'/SDK/aliyun-openapi-php-sdk/aliyun-php-sdk-core/Config.php';
        // 初始化
        $iClientProfile = \DefaultProfile::getProfile("cn-shanghai", env("ACCESS_KEY_ID",''), env("ACCESS_SECRET_ID",''));
        $client = new \DefaultAcsClient($iClientProfile);

        // 设置参数
        $request = new saf\ExecuteRequestRequest();
        // 产品service请参考[公共参数]文档中的Service字段描述
        $request->setService('account_abuse');
        $request->setServiceParameters($json_data);

        // 发起请求
        $response = $client->getAcsResponse($request);

        // 请求正常。
        if ($response->Code == 200) {
            $score = 0;
            // 注册风险识别服务返回的Data参数中的score值，值区间在0~100之间，值越大代表行为的风险程度越大。
            if (isset($response->Data->Score)) {
                $score = $response->Data->Score;
            }
            // 将调用过风险识别的数据存到redis（后台可用list分页）
            $data = json_decode($json_data);
            getRedis()->lPush('list:sms',$data->mobile.'_'.$score);
            return self::handle_score($score);
        }
        switch ($response->Code) {
            case 400: $msg = 'ServiceParameters(事件参数)不合法';break;
            case 402: $msg = '日QPS超过已购规格，限流。';break;
            case 403: $msg = '权限不足，服务未开通或已到期。';break;
            case 404: $msg = 'Service(服务参数）不合法。';break;
            case 500: $msg = '内部服务器错误。';break;
            default : $msg = $response->Code;break;
        }
        // 自定义日志处理 可有可无
        write_log('ali-注册风险识别事件','info','logs/ali_sdk.log','错误信息:'.$msg);
        return 'error';
    }

    public static function handle_score($score)
    {
        $score = intval($score);
        /**
         * 0到35（不含）	低风险	可放过
         * 35（含）到65（不含）	中风险	可打标观察
         * 65（含）到85（不含）	中高风险	可进一步安全验证或限制高危业务使用权限
         * 85（含）到100（含）	高风险	可限制高危业务使用权限
         */
        $bool = false;
        if ($score >= 0 && $score < 65) {
            $bool = true;
        }

        return $bool;
    }

    /**
     * 风险识别数据分页
     * @param $request
     * User: wangxiangqian@julyedu.cn
     * Date: 2019-12-19 20:21
     */
    public static function mobile_lRange($request)
    {

        $page = $request->input('page',1);
        $pageSize = $request->input('limit',50);
        $limit_s = ($page-1) * $pageSize;
        $limit_e = ($limit_s + $pageSize) - 1;
        $list = getRedis()->lRange('list:sms',$limit_s,$limit_e); // 根据分页获取数据

        $lLen = getRedis()->redis->lLen('list:sms'); // 总数
    }
}