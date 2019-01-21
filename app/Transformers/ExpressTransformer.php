<?php

namespace App\Transformers;


class ExpressTransformer extends BaseTransformer
{
    public static function transform($item)
    {
        return $item['kuaidi100']['result'];
    }


}