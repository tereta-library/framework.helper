<?php declare(strict_types=1);

namespace Framework\Helper;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

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
 * @class Framework\Helper\File
 * @package Framework\Helper
 * @link https://tereta.dev
 * @since 2020-2024
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @author Tereta Alexander <tereta.alexander@gmail.com>
 * @copyright 2020-2024 Tereta Alexander
 */
class File
{
    /**
     * @var File|null $instance
     */
    private static ?File $instance = null;

    /**
     * @return File
     */
    public static function getInstance(): File
    {
        if (static::$instance) {
            return static::$instance;
        }

        return static::$instance = new self();
    }

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
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);

        if (!is_dir($dir)) {
            return [];
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filePath = $file->getPathname();
                $relativePath = str_replace($dir . DIRECTORY_SEPARATOR, '', $filePath);

                if (preg_match($regularExpression, $relativePath)) {
                    $files[] = $relativePath;
                }
            }
        }

        return $files;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function remove(string $path): bool
    {
        if (is_file($path)) {
            return unlink($path);
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                rmdir($item->getRealPath());
            } else {
                unlink($item->getRealPath());
            }
        }

        return rmdir($path);
    }
}