<?php

namespace App\Http\Controllers\V1;


use com\unionpay\acp\sdk\AcpService;
use com\unionpay\acp\sdk\SDKConfig;
use Illuminate\Http\Request;

class UnionPayController extends BaseController
{
    protected $merId;
    protected $request;

    public function __construct()
    {
        parent::__construct();
        $this->request = new Request();
        header('Content-type:text/html;charset=utf-8');
        ini_set('date.timezone', 'Asia/Shanghai');
        include_once dirname(dirname(dirname(__FILE__))) . '/Tools/union_pay/sdk/acp_service.php';
        $this->merId = env('UNION_PAY_TEST_MERID'); // 测试号
        if (env('APP_ENV') == 'production') {
            $this->merId = env('UNION_PAY_MERID');;
        }
    }

    /**
     * 银联消费的使用参数
     * @return \Illuminate\Http\JsonResponse
     * User: https://github.com/WXiangQian
     */
    public function consume()
    {

        /**
         * 重要：联调测试时请仔细阅读注释！
         *
         * 产品：跳转网关支付产品<br>
         * 交易：消费：前台跳转，有前台通知应答和后台通知应答<br>
         * 日期： 2015-09<br>
         * 版权： 中国银联<br>
         * 说明：以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己需要，按照技术文档编写。该代码仅供参考，不提供编码性能规范性等方面的保障<br>
         * 提示：该接口参考文档位置：open.unionpay.com帮助中心 下载  产品接口规范  《网关支付产品接口规范》，<br>
         *              《平台接入接口规范-第5部分-附录》（内包含应答码接口规范，全渠道平台银行名称-简码对照表)<br>
         *              《全渠道平台接入接口规范 第3部分 文件接口》（对账文件格式说明）<br>
         * 测试过程中的如果遇到疑问或问题您可以：1）优先在open平台中查找答案：
         *                                    调试过程中的问题或其他问题请在 https://open.unionpay.com/ajweb/help/faq/list 帮助中心 FAQ 搜索解决方案
         *                             测试过程中产生的7位应答码问题疑问请在https://open.unionpay.com/ajweb/help/respCode/respCodeList 输入应答码搜索解决方案
         *                          2） 咨询在线人工支持： open.unionpay.com注册一个用户并登陆在右上角点击“在线客服”，咨询人工QQ测试支持。
         * 交易说明:1）以后台通知或交易状态查询交易确定交易成功,前台通知不能作为判断成功的标准.
         *       2）交易状态查询交易（Form_6_5_Query）建议调用机制：前台类交易建议间隔（5分、10分、30分、60分、120分）发起交易查询，如果查询到结果成功，则不用再查询。（失败，处理中，查询不到订单均可能为中间状态）。也可以建议商户使用payTimeout（支付超时时间），过了这个时间点查询，得到的结果为最终结果。
         */
        $channelType = $this->request->input('channelType', '07');
        $merId = $this->merId;
        $riskRateInfo = $this->request->input('riskRateInfo', '');
        $orderId = $this->request->input('orderId', 0);
        $txnAmt = $this->request->input('txnAmt', 1);
        $txnAmt = $txnAmt * 100;
        $txnTime = $this->request->input('txnTime', 0);
        if ($txnTime == 0) {
            $txnTime = date('YmdHis');
        }
        // 在生产环境测试的时候，交易金额请勿小于1角。
        if (env('APP_ENV') == 'production' && $txnAmt <= 10) {
            return $this->responseError('参数错误');
        }
        // todo 查询数据库中的实际付款金额
        $order_txnAmt = 0;
        // 没有查到则定金异常
        if (!isset($order_txnAmt)) {
            return $this->responseError('订单异常');
        }
        $order_txnAmt = $order_txnAmt * 100;
        // 不一样则认为恶意修改金额 返回错误
        if ($txnAmt != $order_txnAmt) {
            return $this->responseError('警告：请勿非法操作，已通知管理员️');
        }
        $params = array(

            //以下信息非特殊情况不需要改动
            'version' => SDKConfig::getSDKConfig()->version,                 //版本号
            'encoding' => 'utf-8',                  //编码方式
            'txnType' => '01',                      //交易类型
            'txnSubType' => '01',                  //交易子类
            'bizType' => '000201',                  //业务类型
            'frontUrl' => SDKConfig::getSDKConfig()->frontUrl . '?order_id=' . $orderId,  //前台通知地址
            'backUrl' => SDKConfig::getSDKConfig()->backUrl . $txnTime,      //后台通知地址
            'signMethod' => SDKConfig::getSDKConfig()->signMethod,                  //签名方法
            'channelType' => $channelType,                  //渠道类型，07-PC，08-手机
            'accessType' => '0',                  //接入类型
            'currencyCode' => '156',              //交易币种，境内商户固定156

            //TODO 以下信息需要填写
            'merId' => $merId,        //商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
            'orderId' => $orderId,    //商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
            'txnTime' => $txnTime,    //订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
            'txnAmt' => $txnAmt,    //交易金额，单位分，此处默认取demo演示页面传递的参数

            // 订单超时时间。
            // 超过此时间后，除网银交易外，其他交易银联系统会拒绝受理，提示超时。 跳转银行网银交易如果超时后交易成功，会自动退款，大约5个工作日金额返还到持卡人账户。
            // 此时间建议取支付时的北京时间加15分钟。
            // 超过超时时间调查询接口应答origRespCode不是A6或者00的就可以判断为失败。
            'payTimeout' => date('YmdHis', strtotime('+15 minutes')),

            'riskRateInfo' => '{commodityName=' . $riskRateInfo . '}',

            // 请求方保留域，
            // 透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据。
            // 出现部分特殊字符时可能影响解析，请按下面建议的方式填写：
            // 1. 如果能确定内容不会出现&={}[]"'等符号时，可以直接填写数据，建议的方法如下。
            //    'reqReserved' =>'透传信息1|透传信息2|透传信息3',
            // 2. 内容可能出现&={}[]"'符号时：
            // 1) 如果需要对账文件里能显示，可将字符替换成全角＆＝｛｝【】“‘字符（自己写代码，此处不演示）；
            // 2) 如果对账文件没有显示要求，可做一下base64（如下）。
            //    注意控制数据长度，实际传输的数据长度不能超过1024位。
            //    查询、通知等接口解析时使用base64_decode解base64后再对数据做后续解析。
            //    'reqReserved' => base64_encode('任意格式的信息都可以'),

            //TODO 其他特殊用法请查看 special_use_purchase.php
        );

        AcpService::sign($params);
        $uri = SDKConfig::getSDKConfig()->frontTransUrl;
        $html_form = AcpService::createAutoFormHtml($params, $uri);

        $data['url'] = $uri;
        foreach ($html_form as $key => $value) {
            $data['data'][] = ['name' => $key, 'value' => $value];
        }
        return $this->responseData($data);

    }

    /**
     * 验证支付参数是否正确
     * @param $txnTime  订单发送时间
     * @return \Illuminate\Http\JsonResponse
     * User: https://github.com/WXiangQian
     */
    public function unionValidate($txnTime)
    {
        if (isset ($_POST ['signature'])) {
            // 验签失败
            if (!AcpService::validate($_POST)) {
                return $this->response->tag('PARAM_ERROR')->response();
            }
            $orderId = $_POST ['orderId']; //其他字段也可用类似方式获取
            $respCode = $_POST ['respCode'];
            //判断respCode=00、A6后，对涉及资金类的交易，请再发起查询接口查询，确定交易成功后更新数据库。
            if ($respCode == 00 || $respCode == 'A6') {
                // 将下单时间存到redis  key：order_id value：txnTime 查询交易的时候需要使用
                getRedis()->set('str:union:pay:' . $orderId, $txnTime);
                // todo 调用支付回调地址

                return $this->responseSuccess();
            } else {
                return $this->responseError('操作失败，请重试');
            }
        }
        return $this->responseError('参数错误');
    }


    /**
     * 银联退款
     * @return \Illuminate\Http\JsonResponse
     * User: https://github.com/WXiangQian
     */
    public function unionRefund()
    {
        $uid = $this->request->input('uid', 0);
        if (empty($uid) || $uid != 77) die;

        $channelType = $this->request->input('channelType', '07');
        $merId = $this->merId;
        $oid = $this->request->input('orderId', 0);
        $txnAmt = $this->request->input('txnAmt', 1);
        $txnAmt2 = $txnAmt * 100;
        $txnTime = date('YmdHis');
        // todo 查询订单信息
        $order_info = ['plat_oid' => 1];
        if (!$order_info) {
            return $this->responseError('参数错误');
        }
        $origQryId = $order_info['plat_oid'];
        $orderId = time() . rand(1111, 9999); // 生成退款订单号

        /**
         * 重要：联调测试时请仔细阅读注释！
         *
         * 产品：跳转网关支付产品<br>
         * 交易：退货交易：后台资金类交易，有同步应答和后台通知应答<br>
         * 日期： 2015-09<br>
         * 版权： 中国银联<br>
         * 说明：以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己需要，按照技术文档编写。该代码仅供参考，不提供编码性能规范性等方面的保障<br>
         * 该接口参考文档位置：open.unionpay.com帮助中心 下载  产品接口规范  《网关支付产品接口规范》<br>
         *              《平台接入接口规范-第5部分-附录》（内包含应答码接口规范，全渠道平台银行名称-简码对照表）<br>
         * 测试过程中的如果遇到疑问或问题您可以：1）优先在open平台中查找答案：
         *                                    调试过程中的问题或其他问题请在 https://open.unionpay.com/ajweb/help/faq/list 帮助中心 FAQ 搜索解决方案
         *                             测试过程中产生的7位应答码问题疑问请在https://open.unionpay.com/ajweb/help/respCode/respCodeList 输入应答码搜索解决方案
         *                          2） 咨询在线人工支持： open.unionpay.com注册一个用户并登陆在右上角点击“在线客服”，咨询人工QQ测试支持。
         * 交易说明： 1）以后台通知或交易状态查询交易（Form_6_5_Query）确定交易成功，建议发起查询交易的机制：可查询N次（不超过6次），每次时间间隔2N秒发起,即间隔1，2，4，8，16，32S查询（查询到03，04，05继续查询，否则终止查询）
         *        2）退货金额不超过总金额，可以进行多次退货
         *        3）退货能对11个月内的消费做（包括当清算日），支持部分退货或全额退货，到账时间较长，一般1-10个清算日（多数发卡行5天内，但工行可能会10天），所有银行都支持
         */

        $params = array(

            //以下信息非特殊情况不需要改动
            'version' => SDKConfig::getSDKConfig()->version,              //版本号
            'encoding' => 'utf-8',              //编码方式
            'signMethod' => SDKConfig::getSDKConfig()->signMethod,              //签名方法
            'txnType' => '04',                  //交易类型
            'txnSubType' => '00',              //交易子类
            'bizType' => '000201',              //业务类型
            'accessType' => '0',              //接入类型
            'channelType' => $channelType,              //渠道类型
            'backUrl' => 'http://www.specialUrl.com', //后台通知地址

            //TODO 以下信息需要填写
            'orderId' => $orderId,        //商户订单号，8-32位数字字母，不能含“-”或“_”，可以自行定制规则，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
            'merId' => $merId,            //商户代码，请改成自己的测试商户号，此处默认取demo演示页面传递的参数
            'origQryId' => $origQryId, //原消费的queryId，可以从查询接口或者通知接口中获取，此处默认取demo演示页面传递的参数
            'txnTime' => $txnTime,        //订单发送时间，格式为YYYYMMDDhhmmss，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
            'txnAmt' => $txnAmt2,       //交易金额，退货总金额需要小于等于原消费

            // 请求方保留域，
            // 透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据。
            // 出现部分特殊字符时可能影响解析，请按下面建议的方式填写：
            // 1. 如果能确定内容不会出现&={}[]"'等符号时，可以直接填写数据，建议的方法如下。
            //    'reqReserved' =>'透传信息1|透传信息2|透传信息3',
            // 2. 内容可能出现&={}[]"'符号时：
            // 1) 如果需要对账文件里能显示，可将字符替换成全角＆＝｛｝【】“‘字符（自己写代码，此处不演示）；
            // 2) 如果对账文件没有显示要求，可做一下base64（如下）。
            //    注意控制数据长度，实际传输的数据长度不能超过1024位。
            //    查询、通知等接口解析时使用base64_decode解base64后再对数据做后续解析。
            //    'reqReserved' => base64_encode('任意格式的信息都可以'),
        );

        AcpService::sign($params); // 签名
        $url = SDKConfig::getSDKConfig()->backTransUrl;

        $this->handleResult($params, $url);
        // todo 退款成功-需要将退款信息存到新表

        return $this->responseSuccess();
    }

    /**
     * 交易状态查询
     * @param $channelType
     * @param $merId
     * @param $orderId
     * @param $txnTime
     * @return mixed
     * User: https://github.com/WXiangQian
     * Date: 2019-12-20 17:05
     */
    public function queryTrans($channelType, $merId, $orderId, $txnTime)
    {
        $params = array(
            //以下信息非特殊情况不需要改动
            'version' => SDKConfig::getSDKConfig()->version,          //版本号
            'encoding' => 'utf-8',          //编码方式
            'signMethod' => SDKConfig::getSDKConfig()->signMethod,          //签名方法
            'txnType' => '00',              //交易类型
            'txnSubType' => '00',          //交易子类
            'bizType' => '000000',          //业务类型
            'accessType' => '0',          //接入类型
            'channelType' => $channelType,          //渠道类型

            //TODO 以下信息需要填写
            'orderId' => $orderId,    //请修改被查询的交易的订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数
            'merId' => $merId,        //商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
            'txnTime' => $txnTime,    //请修改被查询的交易的订单发送时间，格式为YYYYMMDDhhmmss，此处默认取demo演示页面传递的参数
        );

        AcpService::sign($params); // 签名
        $url = SDKConfig::getSDKConfig()->singleQueryUrl;
        $result_arr = $this->handleResult($params, $url);

        return $result_arr;
    }

    /**
     *
     * 处理银联的回调
     * @param $params
     * @param $url
     * @return mixed
     * User: https://github.com/WXiangQian
     */
    public function handleResult($params, $url)
    {
        $result_arr = AcpService::post($params, $url);
        if (count($result_arr) <= 0) { //没收到200应答的情况
            $this->responseError('退款失败，请查询银联后台状态');
        }

        if (!AcpService::validate($result_arr)) {
            $this->responseError('应答报文验签失败');
        }

        if ($result_arr["respCode"] == "00") {
            //交易已受理，等待接收后台通知更新订单状态，如果通知长时间未收到也可发起交易状态查询
            //TODO
            return $result_arr;
        } else if ($result_arr["respCode"] == "03"
            || $result_arr["respCode"] == "04"
            || $result_arr["respCode"] == "05") {
            //后续需发起交易状态查询交易确定交易状态
            //TODO
            $this->responseError('处理超时，请稍后查询。');
        } else {
            //其他应答码做以失败处理
            //TODO
            $this->responseError('失败：' . $result_arr["respMsg"]);
        }
    }
}