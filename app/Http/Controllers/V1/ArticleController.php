<?php

namespace App\Http\Controllers\V1;


use App\Models\Article;
use App\Transformers\ArticleTransformer;

class ArticleController extends BaseController
{

    /**
     * @SWG\Get(
     *      path="/news/list",
     *      tags={"news"},
     *      operationId="news_list",
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
    public function getNewsList()
    {
        $articles = Article::orderBy('id', 'DESC')->get();

        return $this->responseData(ArticleTransformer::transforms($articles));
    }


}