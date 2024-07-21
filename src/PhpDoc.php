<?php declare(strict_types=1);

namespace Framework\Helper;

use ReflectionMethod;
use ReflectionException;

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
 * @class Framework\Helper\PhpDoc
 * @package Framework\Helper
 * @link https://tereta.dev
 * @author Tereta Alexander <tereta.alexander@gmail.com>
 */
class PhpDoc
{
    /**
     * @param string $class
     * @param string $method
     * @return array
     * @throws ReflectionException
     */
    public static function getMethodVariables(string $class, string $method): array
    {
        $reflectionMethod = new ReflectionMethod($class, $method);
        $variables = [];

        $docComment = $reflectionMethod->getDocComment();
        if (!$docComment) {
            return [];
        }
        if (!preg_match('/\/\*\*(.*)\*\//Usi', $docComment, $match)) {
            return [];
        }
        $docComment = $match[1];

        $lastValue = null;
        foreach (explode("\n", $docComment) as $item) {
            $item = trim($item);
            if ($newVariable = preg_match('/^\* +@([a-z0-9_]+) +(.*) *$/Usi', $item, $matches)) {
                $key = $matches[1];
                $value = $matches[2];

                if (isset($variables[$key]) && !is_array($variables[$key])) {
                    $variables[$key] = [$variables[$key]];
                }

                if (!isset($variables[$key])) {
                    $variables[$key] = $value;
                    $lastValue = &$variables[$key];
                } elseif (is_array($variables[$key])) {
                    $variables[$key][] = $value;

                    $arrayKeys = array_keys($variables[$key]);
                    $lastKey = array_pop($arrayKeys);
                    $lastValue = &$variables[$key][$lastKey];
                }
            }
            if (!$newVariable && $lastValue && preg_match('/^\* +(.*)$/Usi', $item, $matches)) {
                $lastValue .= "\n" . trim($matches[1]);
            }
        }

        return $variables;
    }
}