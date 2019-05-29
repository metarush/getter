<?php

declare(strict_types=1);

namespace MetaRush\Getter;

class Generator
{

    public function validType(string $type): bool
    {
        $validTypes = [
            'string',
            'int',
            'bool',
            'array'
        ];

        return \in_array($type, $validTypes);
    }
}
