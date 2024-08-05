<?php declare(strict_types=1);

namespace Framework\Helper;

/**
 * @class Framework\Helper\ArrayConverter
 */
class ArrayManager
{
    /**
     * @param string $inputKey
     * @param mixed $value
     * @param string $delimiter
     * @return array
     */
    public static function convert(string $inputKey, mixed $value, string $delimiter = '.'): array
    {
        $keys = explode($delimiter, $inputKey);

        $array = [];
        $current = &$array;

        foreach ($keys as $key) {
            if (!isset($current[$key]) || !is_array($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }

        $current = $value;

        return $array;
    }

    public static function fetch(string $inputKey, array $array, string $delimiter = '.'): mixed
    {
        $keys = explode($delimiter, $inputKey);

        $current = &$array;

        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                return null;
            }

            if (!is_array($current[$key])) {
                return $current[$key];
            }

            $current = &$current[$key];
        }

        return $current;
    }

    public static function merge(array $array1, array $array2): array
    {
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                $array1[$key] = self::merge($array1[$key], $value);
            } else {
                $array1[$key] = $value;
            }
        }

        return $array1;
    }
}