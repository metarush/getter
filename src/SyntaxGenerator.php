<?php

declare(strict_types=1);

namespace MetaRush\Getter;

class SyntaxGenerator
{

    /**
     * Get class syntax
     *
     * @param string $className
     * @param array $data
     * @return string
     */
    public function classSyntax(string $className, array $data): string
    {
        $s = 'class ' . $className . "\n";
        $s .= "{\n";

        foreach ($data as $k => $v)
            $s .= $this->fieldSyntax($k, $v);

        $s .= "\n";

        foreach ($data as $k => $v) {
            $type = $this->getType($v);
            $s .= $this->propertySyntax($k, $type);
        }

        $s .= "}\n";

        return $s;
    }

    /**
     * Get class field syntax
     *
     * @param string $fieldName
     * @param type $value
     * @return string
     */
    public function fieldSyntax(string $fieldName, $value): string
    {
        $varValueSyntax = $this->varValueSyntax($value);

        return '    private $' . $fieldName . " = $varValueSyntax;\n";
    }

    /**
     * Get class property syntax
     *
     * @param string $name
     * @param string $type
     * @return string
     * @throws \InvalidArgumentException
     */
    public function propertySyntax(string $name, string $type): string
    {
        if (!$this->validType($type))
            throw new \InvalidArgumentException('Invalid argument: ' . $type);

        $s = '    public function get' . \ucwords($name) . "(): " . $type . "\n";
        $s .= "    {\n";
        $s .= '        return $this->' . $name . ";\n";
        $s .= "    }\n\n";

        return $s;
    }

    /**
     * Get value type
     *
     * @param type $value
     * @return string
     */
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

    /**
     * Check if type is valid
     *
     * @param string $type
     * @return bool
     */
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

    /**
     * Get field value syntax for arrays
     *
     * @param array $a
     * @return type
     */
    public function arraySyntax(array $a)
    {
        $s = '[';

        foreach ($a as $v)
            $s .= $this->varValueSyntax($v) . ', ';

        return \trim($s, ', ') . ']';
    }

    /**
     * Get field value syntax
     *
     * @param type $value
     * @return type
     */
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
