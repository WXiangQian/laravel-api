<?php

namespace App\Services;


class RedisService extends BasicService
{
    public $redis_key;

    public function __construct()
    {
        $this->redis_key = 'str:redis'; //key自定义
    }

    /**
     * 加锁
     * @param $uid
     * @return bool
     * User: 175023117@qq.com
     * Date: 2019-07-21 14:38
     */
    public function setLock($uid)
    {
        $lock = getRedis()->setnx($this->redis_key . $uid, time());

        if (true == $lock) {
            getRedis()->expire($this->redis_key . $uid, 5); // 5秒过期时间
            return true;
        }

        return false;
    }

    /**
     * 解锁
     * @param $uid
     * @return bool|int
     * User: 175023117@qq.com
     * Date: 2019-07-21 14:41
     */
    public function unLock($uid)
    {
        if ($this->IsExistLockKey($uid)) {
            return getRedis()->del($this->redis_key . $uid);
        }

        return true;
    }

    /**
     * 是否上锁
     * @param $uid
     * @return mixed
     * User: 175023117@qq.com
     * Date: 2019-07-21 14:41
     */
    public function isExistLockKey($uid)
    {
        return getRedis()->exists($this->redis_key . $uid);
    }
}
