<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Model ;

class BaseModel extends Model
{
    protected $guarded = ['id'] ;
    protected $hidden = ['password', 'created_at', 'updated_at', 'deleted_at'];


}
