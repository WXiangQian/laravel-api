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
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

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

    /**
     * 相当于php的ip2long函数
     * 将IPV4的字符串互联网协议转换成长整型数字
     * @param $ip
     * @return float|int
     * User: WXiangQian
     */
    public static function getIpNodeNum($ip)
    {
        // $num = ip2long($ip);
        $num = 0;
        $ipArr = explode('.', $ip);
        foreach ($ipArr as $k => $v) {
            $num += $ipArr[$k] * pow(256, (3 - $k));
        }
        return $num;
    }

    /**
     * 自定义写入log
     * @param $title
     * @param $level string 告警级别
     * @param $path string log地址
     * @param $info
     * @throws \Exception
     * User: WXiangQian
     */
    public function writeLog($title,$level,$path,$info)
    {
        $logger = new Logger($title);
        switch($level){
            case 'debug':
                $logger->pushHandler(new StreamHandler($path, Logger::DEBUG));  //100
                break;
            case 'info':
                $logger->pushHandler(new StreamHandler($path, Logger::INFO));  //200
                break;
            case 'waring':
                $logger->pushHandler(new StreamHandler($path, Logger::WARNING)); //300
                break;
            case 'notice':
                $logger->pushHandler(new StreamHandler($path, Logger::NOTICE)); // 250
                break;
            case 'error':
                $logger->pushHandler(new StreamHandler($path, Logger::ERROR)); // 400
                break;
            case 'critical':
                $logger->pushHandler(new StreamHandler($path, Logger::CRITICAL)); //500
                break;
            case 'alert':
                $logger->pushHandler(new StreamHandler($path, Logger::ALERT));  //550
                break;
            case 'emergency':
                $logger->pushHandler(new StreamHandler($path, Logger::EMERGENCY));   //600
                break;
        }
        $logger->pushHandler(new FirePHPHandler());
        $logger->addInfo($info);
    }


    /**
     * 根据某个字段排序 (二维数组)
     * @param $list
     * @param $where ['field'=>'id', 'orderBy'=>'desc']
     * @return mixed
     * User: WXiangQian
     */
    public static function sortListByFiled($list, $where = [])
    {
        if (empty($where) || empty($list)) {
            return $list;
        }

        $sort = SORT_ASC;
        if ($where['orderBy'] == 'desc') {
            $sort = SORT_DESC;
        }
        // array_column() 返回输入数组中某个单一列的值。
        $lastNameList = array_column($list, $where['field']);
        // array_multisort() 函数返回排序数组。
        array_multisort($lastNameList, $sort, $list);

        return $list;
    }
}