<?php

namespace Sd1328\Definition\Reference;

use Sd1328\Definition\Definition;
use Sd1328\Definition\Enum;

/**
 * Class PhpType
 * @see https://www.php.net/manual/en/function.gettype.php
 *
 * @method static PhpType _bool()
 * @method static PhpType _integer()
 * @method static PhpType _float()
 * @method static PhpType _string()
 * @method static PhpType _array()
 * @method static PhpType _object()
 * @method static PhpType _resource()
 * @method static PhpType _resource_closed()
 * @method static PhpType _null()
 * @method static PhpType _unknown()
 *
 * @package Sd1328\Definition\Reference
 */
class PhpType extends Enum
{
    public const BOOL = 'boolean';
    public const INTEGER = 'integer';
    public const FLOAT = 'double';
    public const STRING = 'string';
    public const ARRAY = 'array';
    public const OBJECT = 'object';
    public const RESOURCE = 'resource';
    public const RESOURCE_CLOSED = 'resource (closed)';
    public const NULL = 'NULL';
    public const UNKNOWN = 'unknown type';


    /**
     * @param mixed $value
     * @return PhpType|Definition
     */
    public static function defineByVariable($value): PhpType
    {
        $type = gettype($value);
        return self::get($type);
    }

    protected static function filterUsedValues($key): bool
    {
        return true;
    }
}
