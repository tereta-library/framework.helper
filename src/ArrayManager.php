<?php declare(strict_types=1);

namespace Framework\Helper;

/**
 * ·······································································
 * : _____                        _                     _                :
 * :|_   _|   ___   _ __    ___  | |_    __ _        __| |   ___  __   __:
 * :  | |    / _ \ | '__|  / _ \ | __|  / _` |      / _` |  / _ \ \ \ / /:
 * :  | |   |  __/ | |    |  __/ | |_  | (_| |  _  | (_| | |  __/  \ V / :
 * :  |_|    \___| |_|     \___|  \__|  \__,_| (_)  \__,_|  \___|   \_/  :
 * ·······································································
 * ···························WWW.TERETA.DEV······························
 * ·······································································
 *
 * @class Framework\Helper\ArrayManager
 * @package Framework\Helper
 * @link https://tereta.dev
 * @since 2020-2024
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @author Tereta Alexander <tereta.alexander@gmail.com>
 * @copyright 2020-2024 Tereta Alexander
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

    /**
     * @param string $inputKey
     * @param array $array
     * @param string $delimiter
     * @return mixed
     */
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

    /**
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function merge(array $array1, array $array2): array
    {
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                $array1[$key] = self::merge($array1[$key], $value);
            } elseif (is_int($key)) {
                $array1[] = $value;
            } else {
                $array1[$key] = $value;
            }
        }

        return $array1;
    }
}