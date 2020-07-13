<?php
/**
 * Created by PhpStorm.
 * User: wxiangqian
 * Date: 2020-07-12
 * Time: 22:22
 */


namespace App\Repositories;



use App\Models\Article;

class ArticleRepository
{

    // 上一篇文章id
    protected function getPrevArticleId($id)
    {
        return Article::where('id', '<', $id)->max('id');
    }

    // 下一篇文章id
    protected function getNextArticleId($id)
    {
        return Article::where('id', '>', $id)->min('id');
    }
}