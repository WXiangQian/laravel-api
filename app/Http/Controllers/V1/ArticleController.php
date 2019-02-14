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
     *                  @SWG\Property(property="current_page", type="string", description="现在的页码"),
     *                  @SWG\Property(property="first_page_url", type="string", description="第一页URL"),
     *                  @SWG\Property(property="from", type="integer", description="开始"),
     *                  @SWG\Property(property="last_page", type="string", description="最后一页页码"),
     *                  @SWG\Property(property="last_page_url", type="string", description="最后一页url"),
     *                  @SWG\Property(property="next_page_url", type="integer", description="下一页url"),
     *                  @SWG\Property(property="path", type="string",description="路径"),
     *                  @SWG\Property(property="per_page", type="integer", description="每页显示数量"),
     *                  @SWG\Property(property="prev_page_url", type="string",description="上一页地址"),
     *                  @SWG\Property(property="to", type="integer", description="结束"),
     *                  @SWG\Property(property="total", type="integer", description="总条数"),
     *                  @SWG\Property(property="lists", type="array",
     *                      @SWG\Items(type="object",
     *                          @SWG\Property(property="id", type="integer", description="id"),
     *                          @SWG\Property(property="type", type="string", description="文章类型"),
     *                          @SWG\Property(property="title", type="string", description="标题"),
     *                          @SWG\Property(property="content", type="string", description="内容"),
     *                          @SWG\Property(property="vote", type="string", description="点赞量"),
     *                          @SWG\Property(property="updated_at", type="string", description="最后更新时间"),
     *                      ),
     *                  ),
     *              ),
     *          )
     *      ),
     * )
     */
    public function getArticleList()
    {
        $articles = Article::orderBy('id', 'DESC')->paginate(10);

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
     *                  @SWG\Property(property="pre_article", type="object",description="返回null则无数据",
     *                      @SWG\Property(property="id", type="integer", description="id"),
     *                      @SWG\Property(property="title", type="string", description="标题"),
     *                  ),
     *                  @SWG\Property(property="next_article", type="object",description="返回null则无数据",
     *                      @SWG\Property(property="id", type="integer", description="id"),
     *                      @SWG\Property(property="title", type="string", description="标题"),
     *                  ),
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