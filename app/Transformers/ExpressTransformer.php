<?php

namespace App\Transformers;


class ExpressTransformer extends BaseTransformer
{
    public static function transform($item)
    {
        if (isset($item['kuaidi100']['result'])) {
            return $item['kuaidi100']['result'];
        } else {
            return [
                'status' => 201,
                'message' => '快递公司参数异常：单号不存在或者已经过期',
                'error_code' => 0,
                "data" => [],
                "logistics_company" => "",
                "logistics_bill_no" => ""
            ];
        }
    }


}