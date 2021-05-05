<?php

namespace Faibl\MailjetBundle\Util;

class ArrayUtil
{
    public static function filterEmpty(array $data)
    {
        return array_filter($data, function ($item) {
            return !empty($item);
        });
    }

    public static function filterEmptyRecursive(array $data = []): array
    {
        $data = array_map(function ($value) {
            return is_array($value) ? ArrayUtil::filterEmptyRecursive($value) : $value;
        }, $data);

        return ArrayUtil::filterEmpty($data);
    }

    public static function stringToArray(string $string = null, string $separater = ','): array
    {
        if (!is_string($string) || empty($string)) {
            return [];
        }

        return array_map(function (string $item) {
            return trim($item);
        }, explode($separater, (string) $string));
    }
}
