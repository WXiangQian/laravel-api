<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends BaseModel
{
    use SoftDeletes;
    protected $table = 'articles';
    protected $hidden = ['deleted_at'];

}
