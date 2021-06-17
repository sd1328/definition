<?php

declare(strict_types=1);

namespace Sd1328\Definition;

trait LintingTrait
{
    private static function validateDefinitionValue($value): void
    {
        $type = gettype($value);
        if (
            !in_array($type, ['double', 'integer', 'string'])
        ) {
            throw new IncorrectSpecificationException(
                'Definition [' . static::class . '] "value" must be types: int | float | string.'
            );
        }
    }
}
