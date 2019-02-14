<?php

namespace App\Transformers;


use App\Models\Article;
use App\Models\ArticleType;

class ArticleTransformer extends BaseTransformer
{
    public static function transform($item)
    {
        $article = Article::find($item['id']);
        // Count该文章的点赞量
        $item['vote'] = $article->countVoters();
        // 查找该文章的分类
        $articleType = ArticleType::where('id', $item['type_id'])->first();
        $item['type'] = '';
        if ($articleType) {
            $item['type'] = $articleType->name;
        }
        // 获取上一篇下一篇的内容
        $preArticleId = Article::getPrevArticleId($item['id']);
        $item['pre_article'] = Article::find($preArticleId);


        $nextArticleId = Article::getNextArticleId($item['id']);
        $item['next_article'] = Article::find($nextArticleId);

        return $item;
    }


}