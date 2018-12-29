<?php

namespace App\Http\Controllers\V1;


use App\Models\Article;
use App\Transformers\ArticleTransformer;
use Illuminate\Http\Request;

class ArticleController extends BaseController
{

    /**
     * @SWG\Get(
     *      path="/article/list",
     *      tags={"article"},
     *      operationId="article_list",
     *      summary="获取文章列表",
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
     *                  @SWG\Property(property="id", type="integer", description="id"),
     *                  @SWG\Property(property="type", type="string", description="文章类型"),
     *                  @SWG\Property(property="title", type="string", description="标题"),
     *                  @SWG\Property(property="content", type="string", description="内容"),
     *                  @SWG\Property(property="vote", type="string", description="点赞量"),
     *                  @SWG\Property(property="updated_at", type="string", description="最后更新时间"),
     *              ),
     *          )
     *      ),
     * )
     */
    public function getArticleList()
    {
        $articles = Article::orderBy('id', 'DESC')->paginate(3);

        return $this->responseData(ArticleTransformer::transforms($articles));
    }

    /**
     * @SWG\Get(
     *      path="/article/info",
     *      tags={"article"},
     *      operationId="article_info",
     *      summary="获取文章详情",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      @SWG\Parameter(in="query",name="id",description="id",required=true,type="integer",),
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
     *                  @SWG\Property(property="updated_at", type="string", description="最后更新时间"),
     *              ),
     *          )
     *      ),
     * )
     */
    public function getArticleInfo(Request $request)
    {
        $id = $request->input('id');
        $article = Article::where('id', $id)->first();
        if (!$article) {
            return $this->responseNotFound('没有找到匹配的文章');
        }

        return $this->responseData(ArticleTransformer::transform($article));
    }

    /**
     * @SWG\Get(
     *      path="/article/info/vote",
     *      tags={"article"},
     *      operationId="article_info_vote",
     *      summary="点赞文章详情",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(in="query",name="id",description="id",required=true,type="integer",),
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
    public function voteArticle(Request $request)
    {
        $id = $request->input('id');
        $user = $request->user();
        $article = Article::where('id', $id)->first();
        if (!$article) {
            return $this->responseNotFound('没有找到匹配的文章');
        }

        // 判断是否已点赞
        if ($user->hasVoted($article)) {
            // 取消点赞
            if ($user->cancelVote($article)) {
                return $this->responseSuccess('文章取消点赞成功');
            } else {
                return $this->responseSuccess('文章取消点赞失败');
            }
        } else {
            // 点赞
            if ($user->upVote($article)) {
                return $this->responseSuccess('文章点赞成功');
            } else {
                return $this->responseSuccess('文章点赞失败');
            }
        }
    }
}