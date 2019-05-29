<?php

declare(strict_types=1);

namespace MetaRush\Getter;

class Generator
{

    public function generatedMethod(string $name, string $type): string
    {
        if (!$this->validType($type))
            throw new \InvalidArgumentException('Invalid argument: ' . $type);

        $s = 'public function get' . ucwords($name) . "(): " . $type . "\n";
        $s .= "{\n";
        $s .= '    return $this->' . $name . ";\n";
        $s .= "}\n";

        return $s;
    }

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
