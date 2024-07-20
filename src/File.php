<?php declare(strict_types=1);

namespace Framework\Helper;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class File
{
    /**
     * The function will return list of found files by regular expression
     * For example getFiles(getcwd(), '/app\/modules\/.*\/.*\/Module.php/Usi') will return array with files in format
     *     [
     *         'Vendor/Module/Module.php',
     *         'Vendor/Store/Module.php',
     *         'Google/Search/Module.php',
     *         ...
     *     ];
     *
     * @param string $dir
     * @param string $regularExpression
     * @return array
     */
    public static function getFiles(string $dir, string $regularExpression): array
    {
        $files = [];

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filePath = $file->getPathname();
                $relativePath = str_replace($dir . DIRECTORY_SEPARATOR, '', $filePath);

                if (preg_match($regex, $relativePath)) {
                    $files[] = $relativePath;
                }
            }
        }

        return $files;
    }
}