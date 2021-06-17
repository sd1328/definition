<?php

declare(strict_types=1);

namespace Sd1328\Definition;

use ReflectionClass;
use InvalidArgumentException;
use Generator;

/**
 * Class AbstractDefinition
 * @package Sd1328\Definition
 */
abstract class AbstractDefinition
{
    /**
     * Коллекция объектов справочников - кешируется при первом вызове
     * @var Definition[][]
     */
    private static array $referenceStorage = [];

    /**
     * Получение коллекции справочника
     * @return null|array
     */
    abstract protected static function getCustomDefinitionData(): ?array;

    /**
     * Метод реализует фильтр удаленных элементов,
     * для удаленного элемента возвращает FALSE иначе TRUE
     * @param int $key
     * @return bool
     */
    abstract protected static function filterUsedValues(int $key): bool;

    /**
     * Получение коллекции объектов справочника
     * @return array|Definition[]
     */
    public static function list(): array
    {
        $class = static::class;
        if (!isset(self::$referenceStorage[$class])) {
            self::$referenceStorage[$class] = static::buildList();
        }
        return self::$referenceStorage[$class];
    }

    /**
     * Получение коллекции объектов справочника - только "Используемые"
     * - отфильтрованы условием self::filterUsedValues()
     * @return Generator|Definition[]
     */
    public static function usedList(): Generator
    {
        foreach (static::list() as $value => $definitionItem) {
            if (static::filterUsedValues($value)) {
                yield $value => $definitionItem;
            }
        }
    }

    /**
     * Получение коллекции объектов справочника - только "НЕ Используемые"
     * - отфильтрованы условием ! self::filterUsedValues()
     * @return Generator|Definition[]
     */
    public static function unUsedList(): Generator
    {
        foreach (static::list() as $value => $definitionItem) {
            if (!static::filterUsedValues($value)) {
                yield $value => $definitionItem;
            }
        }
    }

    /**
     * @param $value
     * @return static
     */
    public static function get($value): self
    {
        $list = static::list();
        if (isset($list[$value])) {
            return $list[$value];
        }
        throw new InvalidArgumentException("Value $value not found");
    }

    /**
     * Данные справочника по умолчанию - минимальный набор (enumeration list):
     *  [
     *      (int|float|string) value => [
     *          'name' => (string) name
     *      ],
     *      ...
     *  ]
     * @param ReflectionClass $reflectionClass
     * @return Generator
     */
    private static function getDefaultDefinitionData(ReflectionClass $reflectionClass): Generator
    {
        foreach ($reflectionClass->getConstants() as $constantName => $constantValue) {
            yield $constantValue => ['name' => $constantName];
        }
    }

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
     * @return null|array
     */
    private static function getDefinitionData(ReflectionClass $reflectionClass): array
    {
        $customCollection = static::getCustomDefinitionData();
        if ($customCollection === null) {
            return iterator_to_array(
                self::getDefaultDefinitionData($reflectionClass)
            );
        }
        return $customCollection;
    }

    /**
     * Получение коллекции справочника
     * @return array
     */
    private static function buildList(): array
    {
        $reflection = new ReflectionClass(static::class);

        $class = static::class;
        $list = [];
        foreach (self::getDefinitionData($reflection) as $value => $fields) {
            $definitionItem = new $class();
            $fields = $fields + ['value' => $value];
            foreach ($fields as $fieldName => $fieldValue) {
                $property = $reflection->getProperty($fieldName);
                $property->setAccessible(true);
                $property->setValue($definitionItem, $fieldValue);
            }
            $list[$value] = $definitionItem;
        }
        return $list;
    }
}
