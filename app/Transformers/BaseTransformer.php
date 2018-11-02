<?php

namespace App\Transformers;


abstract class BaseTransformer
{

    public static function transforms($collection)
    {
        // @todo 如果是不是数组进行转换，暂时实现，需要判断是否collection为collection对象
        if (!is_array($collection)) {
            $collection = $collection->toArray();
        }

        if (!$collection) {
            return [];
        }

        if (isset($collection['data'])) {
            $collection['lists'] = $collection['data'];
            unset($collection['data']);

            if ($collection['lists']) {
                $collection['lists'] = array_map(function ($data) {
                    return static::transform($data);
                }, $collection['lists']);
            }

             return $collection;
        }

        return array_map(function ($data) {
            return static::transform($data);
        }, $collection);
    }
}