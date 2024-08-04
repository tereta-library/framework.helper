<?php declare(strict_types=1);

namespace Framework\Helper\Config;

use Framework\Helper\Interface\Config;

/**
 * ···························WWW.TERETA.DEV······························
 * ·······································································
 * : _____                        _                     _                :
 * :|_   _|   ___   _ __    ___  | |_    __ _        __| |   ___  __   __:
 * :  | |    / _ \ | '__|  / _ \ | __|  / _` |      / _` |  / _ \ \ \ / /:
 * :  | |   |  __/ | |    |  __/ | |_  | (_| |  _  | (_| | |  __/  \ V / :
 * :  |_|    \___| |_|     \___|  \__|  \__,_| (_)  \__,_|  \___|   \_/  :
 * ·······································································
 * ·······································································
 *
 * @class Framework\Helper\Php
 * @package Framework\Helper
 * @link https://tereta.dev
 * @since 2020-2024
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @author Tereta Alexander <tereta.alexander@gmail.com>
 * @copyright 2020-2024 Tereta Alexander
 */
class Php implements Config
{
    public function toString(array|object $content): string
    {
        $result = "<?php declare(strict_types=1);\n\n";
        $result .= "return " . $this->arrayToString($content) . ";\n";

        return $result;
    }

    private function arrayToString($array, $indent = 0) {
        $indentation = str_repeat('    ', $indent);
        $arrayCode = "[\n";

        foreach ($array as $key => $value) {
            $item = $indentation . '    ';

            if (is_string($key)) {
                $item .= "'" . addslashes($key) . "' => ";
            } else if (is_int($key)) {
                $item .= $key . ' => ';
            } else {
                continue;
            }

            if (is_array($value)) {
                $item .= $this->arrayToString($value, $indent + 1);
            } elseif (is_bool($value)) {
                $item .= $value ? 'true' : 'false';
            } elseif (is_string($value)) {
                $item .= "'" . addslashes($value) . "'";
            } else {
                continue;
            }

            $arrayCode .= $item . ",\n";
        }

        $arrayCode .= $indentation . ']';

        return $arrayCode;
    }

    public function save(string $path, array|object $content): int
    {
        return file_put_contents($path, $this->toString($content));
    }

    public function load(string $path): array|object
    {
        if (!file_exists($path)) {
            return [];
        }
        return require $path;
    }
}