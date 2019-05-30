<?php

declare(strict_types=1);

namespace MetaRush\Getter;

class Generator
{

    public function generatedClass(string $name, array $data): string
    {
        $s = 'class ' . $name . "\n";
        $s .= "{\n";

        foreach ($data as $k => $v)
            $s .= $this->generatedField($k, $v);

        $s .= "\n";

        foreach ($data as $k => $v) {
            $type = $this->getType($v);
            $s .= $this->generatedGetProperty($k, $type);
        }

        $s .= "}\n";

        return $s;
    }

    public function generatedField(string $name, $value): string
    {
        $varValueSyntax = $this->varValueSyntax($value);

        return '    private $' . $name . " = $varValueSyntax;\n";
    }

    public function generatedGetProperty(string $name, string $type): string
    {
        if (!$this->validType($type))
            throw new \InvalidArgumentException('Invalid argument: ' . $type);

        $s = '    public function get' . \ucwords($name) . "(): " . $type . "\n";
        $s .= "    {\n";
        $s .= '        return $this->' . $name . ";\n";
        $s .= "    }\n\n";

        return $s;
    }

    public function getType($value): string
    {
        if (\is_int($value))
            return 'int';
        if (\is_float($value))
            return 'float';
        if (\is_bool($value))
            return 'bool';
        if (\is_array($value))
            return 'array';

        return 'string';
    }

    public function validType(string $type): bool
    {
        $validTypes = [
            'string',
            'int',
            'float',
            'bool',
            'array'
        ];

        return \in_array($type, $validTypes);
    }

    public function arraySyntax(array $a)
    {
        $s = '[';

        foreach ($a as $v)
            $s .= $this->varValueSyntax($v) . ', ';

        return trim($s, ', ') . ']';
    }

    public function varValueSyntax($value)
    {
        $type = $this->getType($value);

        if ($type == 'string')
            $s = "'{$value}'";
        elseif ($type == 'int')
            $s = $value;
        elseif ($type == 'float')
            $s = $value;
        elseif ($type == 'bool')
            $s = $value ? 'true' : 'false';
        elseif ($type == 'array')
            $s = $this->arraySyntax($value);

        return $s;
    }
}
