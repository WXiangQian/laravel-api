<?php

namespace App\Http\Tools\HaiPay;


class SignUtil
    {

        private $key = ''; /*密钥,22个字符*/
        private $iv  = ''; /*向量，8个或10个字符*/

        public function __construct($key = '',$iv = '')
        {
            $this->key = $key;
            $this->iv = $iv;
        }


    /**
         * 加密
         * @param boolean $status 是否加密
         * @return string 处理过的数据
         */
        public function encrypt($data,$status=false){
            if ($status){
                return base64_encode(openssl_encrypt($data, 'des-ede3-cbc', $this->key, OPENSSL_RAW_DATA,$this->iv));
            }
           return $data;
        }
     
        /**
         * 解密
         * @return string 加密的字符串不是完整的会返回空字符串值
         */
        public function decrypt($data,$miv,$status=false){
            if ($status){
                $k=substr($miv,0,8);
                $i=substr($miv,8,8);
                return openssl_decrypt(base64_decode($data), 'des-ede3-cbc', $k, OPENSSL_RAW_DATA, $i);
            }
            return $data;
        }
    }

?>