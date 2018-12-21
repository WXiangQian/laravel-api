<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Jcc\LaravelVote\CanBeVoted;

class Article extends BaseModel
{
    use SoftDeletes, CanBeVoted;
    protected $table = 'articles';
    protected $hidden = ['deleted_at'];

}
