<?php
namespace App\Http\Controllers\V1;


use App\Http\Tools\HaiPay\RSAUtils;
use App\Http\Tools\HaiPay\SignUtil;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HaiPayController extends BaseController
{
    protected $host;
    protected $request;
    protected $loanType;
    protected $http_clinet;

    public function __construct()
    {
        /**
         * applyNo是当前请求的一个请求号，方便查日志用的，没有其他作用。不与订单信息绑定。
         * uuid
         */
        parent::__construct();
        $this->request = new Request();
        $this->host = 'https://testpm.haiercash.com:9002';
        $this->loanType = 0; // 测试贷款品种代码
        if(env('APP_ENV') == 'production'){
            $this->loanType = 0 ; // todo 线上贷款品种代码
            $this->host = 'https://testpm.haiercash.com:9002'; // todo 线上地址
        }
        $this->http_clinet = new Client();
        require_once  dirname(dirname(dirname(__FILE__))) . '/Tools/hai_pay/SignUtil.php';
        require_once  dirname(dirname(dirname(__FILE__))) . '/Tools/hai_pay/RSAUtils.php';

    }

    /**
     * 贷款申请
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * User: https://github.com/WXiangQian
     */
    public function haiConsume()
    {

        $key = rand(10000000, 99999999);
        $iv  = rand(10000000, 99999999);
        $orderId = $this->request->input('order_id',0);
        // 型号
        $model = $this->request->input('model','');
        // 期数：3、6、9、12
        $applyTnr = $this->request->input('applyTnr',0);
        if (!in_array($applyTnr,[3,6,9,12])) {
            return $this->responseError('参数错误');
        }
        // todo 查询数据库中的实际付款金额
        $payAmt = 0;
        if (!isset($payAmt)) {
            return $this->responseError('订单不存在');
        }
        // todo 小于1w的课程不允许使用海尔金融（根据自己的逻辑实现-可删除判断）
        if ($payAmt < 10000) {
            return $this->responseError('参数错误');
        }

        $post_data = array(
            'uuid' => time().rand(1111,9999), // 每一笔订单都不要重复
            'body' => array(
                'orderSn' => $orderId,
                'loanType' => $this->loanType,
                'payAmt' => $payAmt,
                'orderDate' => date('Y-m-d',time()),
                'applyTnr' => $applyTnr,
                'orderMessage' => array(
                    'cOrderSn' => time(),
                    'topLevel' => '', // todo 一级类目
                    'model' => $model,
                    'sku' => 0,
                    'price' => $payAmt,
                    'num' => 1,
                    'cOrderAmt' => $payAmt,
                    'cOrderPayAmt' => $payAmt
                ),
            )
        );

        $sign = new SignUtil($key, $iv);
        $desData = $sign->encrypt(json_encode($post_data), true);

        $rsa = new RSAUtils();
        $password_ = $rsa->encryptByPublicKey($key . $iv);

        $data = [
            'applyNo' => $orderId,
            'channelNo' => '',    // todo 文档里的渠道编号
            'tradeCode' => '', // todo 内部系统使用，文档里有标识
            'data' => $desData,
            'privatekey' => $rsa->getPrivateKey(),
            'key' => $password_
        ];
        $dataString = json_encode($data);
        $clinetData = [
            'body' => $dataString,
        ];
        $result = $this->http_clinet->request('post', $this->host.'/api/payment/gmorder/loanApplication', $clinetData);

        if ($result->getStatusCode() != 200) {
            return $this->responseError('参数错误');
        }
        $result = json_decode($result->getBody(),true);

        if ($result['head']['retFlag'] != '00000') {
            return $this->responseError($result['head']['retMsg']);
        }

        $resData = $result['body']['data'];
        $resKey = $result['body']['key'];
        $decryptByPublicKey = $rsa->decryptByPrivateKey($resKey);

        $r = $sign->decrypt($resData, $decryptByPublicKey, true);
        $url = '';
        if ($r) {
            $r = json_decode($r, true);
            if (!empty($r['url'])) {
                $url = $r['url'];
            }
        }
        return $this->responseData(['url'=>$url]);
    }

    /**
     * 贷款回调(返回格式是海尔强制要求的)
     * User: https://github.com/WXiangQian
     */
    public function haiCallback()
    {
        $post = file_get_contents('php://input');
        $rs = json_decode(stripslashes($post),true);
        if(empty($rs['key']) || empty($rs['data'])){
            echo json_encode(array('status'=>1,'msg'=>'key值或data值为空'),JSON_UNESCAPED_UNICODE); die;
        }
        $sign = new SignUtil();
        $rsa  = new RSAUtils();
        $decryptByPublicKey=$rsa->decryptByPrivateKey($rs['key']);
        $r = $sign->decrypt($rs['data'],$decryptByPublicKey,true);
        if(!$r){
            echo json_encode(array('status'=>1,'msg'=>'解密失败'),JSON_UNESCAPED_UNICODE); die;
        }
        $r = json_decode($r,true);
        if(!$r['ordersn']){
            echo json_encode(array('status'=>1,'msg'=>'没有订单编号'),JSON_UNESCAPED_UNICODE); die;
        }
        $out_trade_no   = $r['ordersn']; //订单号
        $transStatus    = $r['body']['outSts']; //订单状态  等于10 成功 等于11 失败 其余状态不用处理 //01审批中02审批通过03审批拒绝04 贷款已取消05 客人以确认提交(订单保存)06 审批退回(客人
        $trade_no       = $r['body']['orderNo']; //流水号
       // $notify_time    = time();       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。

        if($transStatus == '02') {
            // todo 调用支付的回调逻辑

            echo json_encode(array('status'=>1,'msg'=>'02处理成功'),JSON_UNESCAPED_UNICODE); die;
        }
        echo json_encode(array('status'=>1,'msg'=>'处理成功'),JSON_UNESCAPED_UNICODE); die;

    }
}