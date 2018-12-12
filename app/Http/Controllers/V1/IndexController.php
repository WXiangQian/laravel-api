<?php

namespace App\Http\Controllers\V1;


use App\Transformers\UserInfoTransformer;
use Illuminate\Http\Request;
use JWTAuth;

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
     *      path="/login",
     *      tags={"user"},
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
     *              @SWG\Property(property="mobile",description="手机号",type="string"),
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
     *                  @SWG\Property(property="access_token", type="string", description="token"),
     *              ),
     *          )
     *      ),
     * )
     */
    public function login(Request $request)
    {
        $rules = [
            'mobile'   => 'required',
            'password' => 'required|string|min:6|max:20',
        ];

        // 验证参数，如果验证失败，则会抛出 ValidationException 的异常
        $params = $this->validate($request, $rules);
        // 使用 Auth 登录用户，如果登录成功，则返回 200 的 code 和 token，如果登录失败则返回账号或密码错误
        $token = JWTAuth::attempt($params);
        if (!$token) {
            return $this->responseError('账号或密码错误');
        }

        return $this->responseData(['access_token' => $token]);
    }



}