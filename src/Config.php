<?php declare(strict_types=1);

namespace Framework\Helper;

use Exception;
use Framework\Helper\Interface\Config as ConfigInterface;
use Framework\Pattern\ValueObject;
/**
 * Framework\Helper\Config
 */
class Config extends ValueObject
{
    private array $map = [
        'php' => 'Framework\Helper\Config\Php'
    ];

    private ConfigInterface $adapter;

    public function __construct(string $adapter = 'php', array|object &$data = [])
    {
        $this->setAdapter($adapter);

        parent::__construct($data);
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
        $this->adapter->save($path, $this->getData());

        return $this;
    }

    public function load(string $path): static
    {
        $this->setData($this->adapter->load($path));

        return $this;
    }
}