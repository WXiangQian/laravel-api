<?php

namespace App\Http\Controllers\V1;


use App\Http\Requests\RegisterRequest;
use App\Models\Article;
use App\Models\User;
use App\Transformers\ArticleTransformer;
use Illuminate\Http\Request;
use JWTAuth;

/**
 * Class UserController
 * @author WXiangQian <175023117@qq.com>
 * @package App\Http\Controllers\V1
 */
class UserController extends BaseController
{
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
            'mobile' => 'required',
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

    /**
     * @SWG\Post(
     *      path="/register",
     *      tags={"user"},
     *      operationId="register",
     *      summary="注册",
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
     *                  @SWG\Property(property="user", type="array",
     *                      @SWG\Items(type="object",
     *                          @SWG\Property(property="id", type="integer",description="id"),
     *                          @SWG\Property(property="mobile", type="string",description="手机号"),
     *                          @SWG\Property(property="nickname", type="string",description="昵称"),
     *                          @SWG\Property(property="created_at", type="string",description="创建时间"),
     *                      ),
     *                  ),
     *              ),
     *          )
     *      ),
     * )
     */
    public function register(RegisterRequest $request)
    {
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        // 注册用户
        $user = User::create([
            'mobile' => $mobile,
            'password' => bcrypt($password),
            'nickname' => encryptedPhoneNumber($mobile)
        ]);
        // 获取token
        $token = JWTAuth::fromUser($user);
        if (!$token) {
            return $this->responseError('注册失败，请重试');
        }

        return $this->responseData([
            'access_token' => $token,
            'user' => $user
        ]);
    }

    /**
     * @SWG\Get(
     *      path="/user/vote",
     *      tags={"user"},
     *      operationId="user_vote",
     *      summary="获取用户已点赞的记录",
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
     *                  @SWG\Property(property="id", type="integer", description="id"),
     *                  @SWG\Property(property="type", type="string", description="文章类型"),
     *                  @SWG\Property(property="title", type="string", description="标题"),
     *                  @SWG\Property(property="content", type="string", description="内容"),
     *                  @SWG\Property(property="vote", type="string", description="点赞量"),
     *              ),
     *          )
     *      ),
     * )
     */
    public function getVoteArticleByUserId(Request $request)
    {
        $user = $request->user();

        // 获取用户已点赞的记录
        $articles = $user->votedItems(Article::class)->get()->toArray();
        return $this->responseData(ArticleTransformer::transforms($articles));
    }
}