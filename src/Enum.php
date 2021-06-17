<?php

declare(strict_types=1);

namespace Sd1328\Definition;

/**
 * Class AbstractEnum
 *
 * Enum - частный случай (простой список констант)
 *
 * @package Sd1328\Definition
 */
abstract class Enum extends Definition
{
    protected static function getCustomDefinitionData(): ?array
    {
        return null;
    }
}
