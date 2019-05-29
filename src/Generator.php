<?php

declare(strict_types=1);

namespace MetaRush\Getter;

class Generator
{

    public function generatedField(string $name, string $value): string
    {
        return 'private $' . $name . " = '" . $value . "';\n";
    }

    public function generatedGetProperty(string $name, string $type): string
    {
        if (!$this->validType($type))
            throw new \InvalidArgumentException('Invalid argument: ' . $type);

        $s = 'public function get' . \ucwords($name) . "(): " . $type . "\n";
        $s .= "{\n";
        $s .= '    return $this->' . $name . ";\n";
        $s .= "}\n\n";

        return $s;
    }

    public function getType($value): string
    {
        if (is_array($value))
            return 'array';
        if (is_bool($value))
            return 'bool';
        if (is_int($value))
            return 'int';

        return 'string';
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
