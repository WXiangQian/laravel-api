<?php

namespace App\Http\Controllers\V1;

use App\Jobs\DemoJob;
use App\Services\ALiService;
use App\Services\CaptchaVerifier;
use App\Services\RedisService;
use App\Transformers\ExpressTransformer;
use App\Transformers\UserInfoTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Req;
use Wythe\Logistics\Logistics;

/**
 * Class IndexController
 * @author WXiangQian <175023117@qq.com>
 * @package App\Http\Controllers\V1
 */
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
            throw new LogicException($code);
            return $this->responseError('请输入要查询的快递单号');
        }
        $logistics = new Logistics();

        $data = $logistics->query($code, 'kuaidi100');
        return $this->responseData(ExpressTransformer::transform($data));
    }

    /**
     * @SWG\Post(
     *      path="/wangyi/verify",
     *      tags={"public"},
     *      operationId="wangyi_verify",
     *      summary="网易易盾验证",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          in="body",
     *          name="data",
     *          description="",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="validate",description="二次校验验证码",type="string"),
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
     *              ),
     *          )
     *      ),
     * )
     */
    public function wangyiVerify(Request $request)
    {
        $validate = $request->input('validate');

        // 文档地址：http://support.dun.163.com/documents/15588062143475712?docId=69218161355051008

        $CaptchaVerifier = new CaptchaVerifier(config('captcha.captcha.CAPTCHA_ID'),config('captcha.captcha.SECRET_ID'),config('captcha.captcha.SECRET_KEY'));
        //通过则返回true
        $validatePass = $CaptchaVerifier->verify($validate);
        if (!$validatePass) {
            return $this->responseError('验证不通过');
        }
        return $this->responseSuccess('验证通过');
    }

    public function redis_lock(RedisService $redisService)
    {
        $uid = 1;
        // 是否是锁状态
        if($redisService->isExistLockKey($uid)){
            return $this->responseError('服务器繁忙，请稍后再试~');
        }
        $redisService->setLock($uid); //加锁

        // 逻辑处理 随便操作
        // ~~~~~~~~~~~~~~~~~

        $redisService->unLock($uid);//解锁

        return 1;
    }

    public function queue_demo()
    {
        $num = rand(1,999999999);
        // 这个任务将被分发到默认队列...
        DemoJob::dispatch($num);
    }

    /**
     * 防垃圾手机号注册问题
     * @param $phone_num
     * @param $area_code
     * @return \Illuminate\Http\JsonResponse
     * User: WXiangQian <175023117@qq.com>
     * Date: 2019-12-02 12:21
     */
    public function ali_api_check($phone_num,$area_code)
    {
        $new_phone_num = $phone_num;
        if ($area_code != '0086') {
            $new_phone_num = $area_code.'-'.$new_phone_num;
        }
        $arr = [
            'mobile'=>$new_phone_num,
            'operateTime'=>time(),
            'ip'=>ip2long(Req::ip()),
        ];
        if (!empty($_SERVER['HTTP_REFERER'])) $arr['refer'] = $_SERVER["HTTP_REFERER"];
        if (!empty($_SERVER['HTTP_USER_AGENT'])) $arr['userAgent'] = $_SERVER["HTTP_USER_AGENT"];

        $json_data = json_encode($arr);
        $ali_res = ALiService::run($json_data);

        if ($ali_res !== 'error' && $ali_res === false) {
            // 有风险
            return $this->responseError('手机号码异常，请联系客服');
        }
        return $this->responseSuccess();
    }

    /**
     * 获取当前用户的信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * User: WXiangQian
     * Date: 2020-02-24 18:14
     */
    public function wx_login(Request $request)
    {
        $code = trim($request->input('code'));

        // appid和secret在微信小程序后台可以看到，
        // js_code为使用wx.login登录时获取到的code参数数据，
        $url  = "https://api.weixin.qq.com/sns/jscode2session?appid=".env('XCX_APP_ID')."&secret=".ENV('XCX_APP_SECRET')."&js_code={$code}&grant_type=authorization_code";

        $apiData=file_get_contents($url);
        $result = json_decode($apiData, true);

        //获取用户信息(openID，头像，昵称等等 )，然后保存
        if(!isset($result['errcode'])){
            return $this->responseData($result);
        }else{
            return $this->responseError('获取用户信息失败 '.$result['errmsg']);
        }
    }
}