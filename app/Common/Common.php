<?php
/**
 * Created by PhpStorm.
 * User: wxiangqian
 * Date: 2020-08-05
 * Time: 10:32
 */
namespace App\Common;

use App\Exceptions\LogicException;
use App\Services\RedisService;

class Common
{

    const OPEN_SSL_METHOD = 'AES-192-CBC';

    /**
     * 生成key和iv的地址：https://asecuritysite.com/encryption/keygen
     *              https://asecuritysite.com/encryption/PBKDF2z
     */
    /**
     * @param string $string 需要加密的字符串
     * @return string
     */
    public static function encrypt($string)
    {
        // openssl_encrypt 加密不同Mcrypt，对秘钥长度要求，超出16加密结果不变
        $data = openssl_encrypt($string, self::OPEN_SSL_METHOD,pack('H*', env('ENCRYPT_KEY')), OPENSSL_RAW_DATA,pack('H*', env('ENCRYPT_IV')));

        $data = base64_encode($data);
        return $data;
    }
    /**
     * @param string $string 需要解密的字符串
     * @return string
     */
    public static function decrypt($string)
    {
        $decrypted = openssl_decrypt(base64_decode($string), self::OPEN_SSL_METHOD,  pack('H*', env('ENCRYPT_KEY')), OPENSSL_RAW_DATA,pack('H*', env('ENCRYPT_IV')));

        return $decrypted;
    }


    /**
     * redis 锁
     * @param RedisService $redisService
     * @return int
     * @throws LogicException
     * User: WXiangQian
     */
    public static function redisLock(RedisService $redisService)
    {
        $uid = 1;
        // 是否是锁状态
        if($redisService->isExistLockKey($uid)){
            throw new LogicException(5000);
        }
        $redisService->setLock($uid); //加锁

        // todo 逻辑处理 随便操作
        // ~~~~~~~~~~~~~~~~~

        $redisService->unLock($uid);//解锁

        return 1;
    }
}