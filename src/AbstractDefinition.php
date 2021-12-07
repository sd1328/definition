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
    abstract protected static function getDefinitionData(ReflectionClass $reflection): iterable;

    /**
     * Метод реализует фильтр удаленных элементов,
     * для удаленного элемента возвращает FALSE иначе TRUE
     * @param int|string|float $key
     * @return bool
     */
    abstract protected static function filterUsedValues($key): bool;

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
     * Получение коллекции справочника
     * @return array
     */
    private static function buildList(): array
    {
        $class = static::class;
        $reflection = new ReflectionClass($class);
        $list = [];
        foreach (static::getDefinitionData($reflection) as $value => $fields) {
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
