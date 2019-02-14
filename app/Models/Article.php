<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Jcc\LaravelVote\CanBeVoted;

class Article extends BaseModel
{
    use SoftDeletes, CanBeVoted;
    protected $vote = User::class;
    protected $table = 'articles';
    protected $hidden = ['deleted_at'];

    // 上一篇文章id
    protected function getPrevArticleId($id)
    {
        return self::where('id', '<', $id)->max('id');
    }

    // 下一篇文章id
    protected function getNextArticleId($id)
    {
        return self::where('id', '>', $id)->min('id');
    }
}
