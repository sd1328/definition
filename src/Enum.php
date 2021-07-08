<?php

declare(strict_types=1);

namespace Sd1328\Definition;

use Generator;
use ReflectionClass;

/**
 * Class AbstractEnum
 *
 * Enum - частный случай (простой список констант)
 *
 * @package Sd1328\Definition
 */
abstract class Enum extends Definition
{
    /**
     * Данные справочника:
     *  [
     *      (int|float|string) value => [
     *          'name' => (string) name,
     *          'someField' => 'someValue'
     *          ....
     *      ],
     *      ...
     *  ]
     * @param ReflectionClass $reflection
     * @return iterable
     */
    protected static function getDefinitionData(ReflectionClass $reflection): iterable
    {
        return self::getDefaultDefinitionData($reflection);
    }

    /**
     * Данные справочника по умолчанию - минимальный набор (enumeration list):
     *  [
     *      (int|float|string) value => [
     *          'name' => (string) name
     *      ],
     *      ...
     *  ]
     * @param ReflectionClass $reflection
     * @return Generator
     */
    private static function getDefaultDefinitionData(ReflectionClass $reflection): Generator
    {
        foreach ($reflection->getConstants() as $constantName => $constantValue) {
            yield $constantValue => ['name' => $constantName];
        }
    }
}
