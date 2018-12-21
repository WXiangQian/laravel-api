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

        return $item;
    }


}