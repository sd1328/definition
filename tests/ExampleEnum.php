<?php


namespace Sd1328\Definition\Tests;


use Sd1328\Definition\Enum;

class ExampleEnum extends Enum
{
    protected static function filterUsedValues(int $key): bool
    {
        return true;
    }
}
