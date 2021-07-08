<?php

declare(strict_types=1);

namespace Sd1328\Definition\Linting\Constraint;

use Sd1328\Definition\Reference\PhpType;

class DefinitionValueType
{
    public const EXPECTED_TYPES = [
        PhpType::STRING,
        PhpType::INTEGER,
        PhpType::FLOAT
    ];

    public function __invoke($value)
    {
        $type = gettype($value);
        return in_array($type, self::EXPECTED_TYPES);
    }
}
