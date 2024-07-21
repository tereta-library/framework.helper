<?php declare(strict_types=1);

namespace Framework\Helper;

use Exception;
use Framework\Helper\Interface\Config as ConfigInterface;
use Framework\Pattern\ValueObject;

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
 * @class Framework\Helper\Config
 * @package Framework\Helper
 * @link https://tereta.dev
 * @author Tereta Alexander <tereta.alexander@gmail.com>
 */
class Config extends ValueObject
{
    private array $map = [
        'php' => 'Framework\Helper\Config\Php'
    ];

    private ConfigInterface $adapter;

    public function __construct(string $adapter = 'php', private array|object &$data = [])
    {
        parent::__construct($data);
        $this->setAdapter($adapter);
    }

    public function setAdapter(string $adapter): static
    {
        $class = $this->map[$adapter] ?? null;
        if (!$class) throw new Exception(
            "The {$adapter} not found. " .
            "Use one of " . implode(", ", array_keys($this->map)) . " adapters."
        );

        $this->adapter = new $class();

        return $this;
    }

    public function save(string $path): static
    {
        $this->adapter->save($path, $this->data);

        return $this;
    }

    public function load(string $path): static
    {
        $this->setData($this->adapter->load($path));

        return $this;
    }
}