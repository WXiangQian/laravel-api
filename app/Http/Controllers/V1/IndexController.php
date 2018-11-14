<?php

namespace App\Http\Controllers\V1;


use App\Models\User;
use App\Transformers\UserInfoTransformer;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class IndexController extends BaseController
{

    protected $auth;
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

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
        $urls = [
            'https://blog.csdn.net/qq175023117/article/details/84067068',
            'https://blog.csdn.net/qq175023117/article/details/83898955',
            'https://blog.csdn.net/qq175023117/article/details/83986872',
            'https://blog.csdn.net/qq175023117/article/details/83989255',
            'https://blog.csdn.net/qq175023117/article/details/81668473',
            'https://blog.csdn.net/qq175023117/article/details/81708763',
            'https://blog.csdn.net/qq175023117/article/details/81742567',
            'https://blog.csdn.net/qq175023117/article/details/81866873',
            'https://blog.csdn.net/qq175023117/article/details/81908954',
            'https://blog.csdn.net/qq175023117/article/details/81948900',
            'https://blog.csdn.net/qq175023117/article/details/82260221',
            'https://blog.csdn.net/qq175023117/article/details/82690603',
            'https://blog.csdn.net/qq175023117/article/details/82734794',
            'https://blog.csdn.net/qq175023117/article/details/82881877',
            'https://blog.csdn.net/qq175023117/article/details/82977595',
            'https://blog.csdn.net/qq175023117/article/details/83149299',
            'https://blog.csdn.net/qq175023117/article/details/83653125',
            'https://blog.csdn.net/qq175023117/article/details/83862171',
            'https://blog.csdn.net/qq175023117/article/details/80681533',
            'https://blog.csdn.net/qq175023117/article/details/80680777',
            'https://blog.csdn.net/qq175023117/article/details/80931480',
            'https://blog.csdn.net/qq175023117/article/details/80931383',
            'https://blog.csdn.net/qq175023117/article/details/80847269',
            'https://blog.csdn.net/qq175023117/article/details/80839821',
            'https://blog.csdn.net/qq175023117/article/details/80839719',
            'https://blog.csdn.net/qq175023117/article/details/80839605',
            'https://blog.csdn.net/qq175023117/article/details/80839445',
            'https://blog.csdn.net/qq175023117/article/details/80688659',
            'https://blog.csdn.net/qq175023117/article/details/80693844',
            'https://blog.csdn.net/qq175023117/article/details/80681391',
            'https://blog.csdn.net/qq175023117/article/details/80681234',
            'https://blog.csdn.net/qq175023117/article/details/80681180',
            'https://blog.csdn.net/qq175023117/article/details/80681079',
            'https://blog.csdn.net/qq175023117/article/details/80680837',
            'https://blog.csdn.net/qq175023117/article/details/83862216'
        ];

        $key = array_rand($urls);

        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $urls[$key]);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//绕过ssl验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $this->responseData($data);
        $data = [
            'id' => 1,
            'name' => 'test测试',
        ];
        return $this->responseData(UserInfoTransformer::transform($data));
    }

    /**
     * @SWG\Get(
     *      path="/news",
     *      tags={"public"},
     *      operationId="news",
     *      summary="获取咨询列表数据",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
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
    public function news(Request $request)
    {
        $user = $request->user();
        $data = [];
        for ($i = 0; $i <= 10; $i++) {
            $data[$i] = [
                'id' => $i,
                'title' => '测试title'.$i
            ];
        }
        return $this->responseData($user);
    }

    /**
     * @SWG\Post(
     *      path="/login",
     *      tags={"public"},
     *      operationId="login",
     *      summary="登陆",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="body",
     *          name="data",
     *          description="",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="email",description="邮箱",type="string"),
     *              @SWG\Property(property="password",description="密码",type="string"),
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
     *
     *              ),
     *          )
     *      ),
     * )
     */
    public function login(Request $request)
    {
        // 验证规则，由于业务需求，这里我更改了一下登录的用户名，使用手机号码登录
        $rules = [
            'email'   => [
                'required',
            ],
            'password' => 'required|string|min:6|max:20',
        ];

        // 验证参数，如果验证失败，则会抛出 ValidationException 的异常
        $params = $this->validate($request, $rules);
        $user = User::where('email',$params['email'])->first();
        // 使用 Auth 登录用户，如果登录成功，则返回 201 的 code 和 token，如果登录失败则返回
        $token =  $this->auth->fromUser($user);
        if ($token) {
            return $this->responseData(['access_token' => $token]);
        } else {
            $this->responseError('账号或密码错误');
        }

    }



}