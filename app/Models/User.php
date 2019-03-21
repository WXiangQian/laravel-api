<?php

namespace App\Models;

use Jcc\LaravelVote\Vote;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, Vote;
    protected $table = 'users';
    protected $fillable = ['nickname', 'password', 'mobile'];
    #定义是否默认维护时间，默认是true.改为false，则以下时间相关设定无效
    public $timestamps = true;
    protected $hidden = [
        'password',
    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


}
