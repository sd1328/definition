<?php


namespace Sd1328\Definition\Tests;


use ReflectionClass;
use Sd1328\Definition\Definition;

class ExampleReferenceOne extends Definition
{
    protected static function getDefinitionData(ReflectionClass $reflection): iterable
    {
        return [
            'one' => [
                'name' => 'one',
                'someField' => 'someValue',
            ],
        ];
    }

    protected static function filterUsedValues(int $key): bool
    {
        return true;
    }
}
