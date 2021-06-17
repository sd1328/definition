<?php


namespace Sd1328\Definition\Tests;


use Sd1328\Definition\Definition;

class ExampleReferenceOne extends Definition
{

    protected static function getCustomDefinitionData(): ?array
    {
        return null;
    }

    protected static function filterUsedValues(int $key): bool
    {
        return true;
    }
}
