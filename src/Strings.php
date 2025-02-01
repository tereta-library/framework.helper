<?php declare(strict_types=1);

namespace Framework\Helper;

use Framework\Pattern\Singleton;

/**
 * @package Framework\Helper
 * @class Framework\Helper\Strings
 */
class Strings extends Singleton
{
    /**
     * @param string|int|object ...$keys
     * @return string
     */
    public static function generateKey(string|int|object|null|bool|float ...$keys): string
    {
        $result = [];
        foreach ($keys as $key) {
            switch(true){
                case(is_array($key)):
                    $result[] = 'a:' . hash('sha256', serialize($key));
                    break;
                case(is_object($key)):
                    $result[] = 'o:' . spl_object_id($key);
                    break;
                case(is_string($key)):
                    $result[] = 's:' . str_replace(":", "::", $key);
                    break;
                case(is_null($key)):
                    $result[] = 'n:';
                case(is_bool($key)):
                    $result[] = 'b:' . ($key ? '1' : '0');
                case(is_float($key)):
                    $result[] = 'f:' . $key;
                default:
                    $result[] = 'd:' . $key;
                    break;
            }
        }

        return base64_encode(implode(':', $result));
    }
}