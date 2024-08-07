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
 * @since 2020-2024
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @author Tereta Alexander <tereta.alexander@gmail.com>
 * @copyright 2020-2024 Tereta Alexander
 */
class Config extends ValueObject
{
    /**
     * @var array|string[]
     */
    private array $adapterArray = [
        'php' => 'Framework\Helper\Config\Php'
    ];

    /**
     * @var string|null $path
     */
    private ?string $path = null;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $adapter;

    /**
     * @param string $adapter
     * @param array|object $data
     * @throws Exception
     */
    public function __construct(string $adapter = 'php', private array|object &$data = [])
    {
        parent::__construct($data);
        $this->setAdapter($adapter);
    }

    /**
     * @param string $adapter
     * @return $this
     * @throws Exception
     */
    public function setAdapter(string $adapter): static
    {
        $class = $this->adapterArray[$adapter] ?? null;
        if (!$class) throw new Exception(
            "The {$adapter} not found. " .
            "Use one of " . implode(", ", array_keys($this->map)) . " adapters."
        );

        $this->adapter = new $class();

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value): static
    {
        $source = $this->getData();
        $config = ArrayManager::convert($key, $value);
        $source = ArrayManager::merge($source, $config);
        $this->setData($source);

        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return ArrayManager::fetch($key, $this->getData());
    }

    /**
     * @param string|null $path
     * @return $this
     * @throws Exception
     */
    public function save(?string $path = null): static
    {
        if ($path) $this->path = $path;
        if (!$this->path) throw new Exception('Path to save configuration is not set.');

        $this->adapter->save($this->path, $this->getData());

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function load(string $path): static
    {
        $this->path = $path;
        $this->setData($this->adapter->load($path));

        return $this;
    }
}