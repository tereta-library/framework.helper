<?php declare(strict_types=1);

namespace Framework\Helper\Config;

use Framework\Helper\Interface\Config;

/**
 * @class Framework\Helper\Config\Php
 */
class Php implements Config
{
    public function toString(array|object $content): string
    {
        $result = "<?php declare(strict_types=1);\n\n";
        $result .= "return " . $this->arrayToString($content) . "\n";

        return $result;
    }

    private function arrayToString($array, $indent = 0) {
        $indentation = str_repeat('    ', $indent);
        $arrayCode = "[\n";

        foreach ($array as $key => $value) {
            $arrayCode .= $indentation . '    ';

            if (is_string($key)) {
                $arrayCode .= "'" . addslashes($key) . "' => ";
            } else {
                $arrayCode .= $key . ' => ';
            }

            if (is_array($value)) {
                $arrayCode .= $this->arrayToString($value, $indent + 1);
            } elseif (is_string($value)) {
                $arrayCode .= "'" . addslashes($value) . "'";
            } else {
                $arrayCode .= $value;
            }

            $arrayCode .= ",\n";
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
        return require $path;
    }
}