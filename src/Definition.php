<?php

declare(strict_types=1);

namespace Sd1328\Definition;

use InvalidArgumentException;

/**
 * Class AbstractDefinition
 *
 * @property-read float|int|string $value
 * @property-read string $name
 *
 * @package Sd1328\Definition
 */
abstract class Definition extends AbstractDefinition
{
    /**
     * @var float|int|string|null
     */
    protected $value = null;

    /**
     * @var string
     */
    protected string $name = '';

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new IncorrectSpecificationException("Property $name not define");
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return AbstractDefinition|Definition
     */
    public static function __callStatic(string $name, array $arguments)
    {
        // class::_resource() -> class::RESOURCE
        // class::_resource_closed() -> class::RESOURCE_CLOSED
        $constName = static::class . '::' . strtoupper(substr($name, 1));
        if (defined($constName)) {
            return static::get(constant($constName));
        }
        throw new InvalidArgumentException("$name");
    }

    public function isUsed(): bool
    {
        return static::filterUsedValues($this->value);
    }
}
