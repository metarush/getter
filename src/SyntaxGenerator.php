<?php

declare(strict_types=1);

namespace MetaRush\Getter;

class SyntaxGenerator
{

    private $cfg;

    public function __construct(Config $cfg)
    {
        $this->cfg = $cfg;
    }

    /**
     * Get class syntax
     *
     * @return string
     */
    public function classSyntax(): string
    {
        $className = $this->cfg->getClassName();
        $data = $this->cfg->getData();
        $classToExtend = $this->cfg->getExtendedClass();
        $constructorType = $this->cfg->getConstructorType();

        if ($classToExtend)
            $className = $className . ' extends ' . $classToExtend;

        $s = 'class ' . $className . "\n";
        $s .= "{\n";

        foreach ($data as $k => $v)
            $s .= $this->fieldSyntax($k, $v);

        $s .= "\n";

        if ($constructorType) {
            if ($constructorType === Config::CONSTRUCTOR_CALL_PARENT)
                $s .= $this->constructorWithCallParentSyntax();
            elseif ($constructorType === Config::CONSTRUCTOR_DATA_REPLACER)
                $s .= $this->constructorWithDataReplacerSyntax(false);
            elseif ($constructorType === Config::CONSTRUCTOR_BOTH)
                $s .= $this->constructorWithDataReplacerSyntax(true);
        }

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
     * @throws Exception
     */
    public function propertySyntax(string $name, string $type): string
    {
        if (!$this->validType($type))
            throw new Exception('Invalid argument: ' . $type);

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

        if ($this->cfg->getDummifyValues())
            $value = $this->dummifyValue($value);

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

    /**
     * Convert $value to dummy value but retain its type
     *
     * @param mixed $value
     * @return boolean|string|float|int|array
     */
    public function dummifyValue($value)
    {
        $type = $this->getType($value);

        if ($type == 'string')
            $v = 'x';
        elseif ($type == 'int')
            $v = 1;
        elseif ($type == 'float')
            $v = 1.2;
        elseif ($type == 'bool')
            $v = true;
        elseif ($type == 'array')
            $v = ['x'];

        return $v;
    }

    /**
     * Get constructor with data replacer syntax
     *
     * @param bool $callParent
     * @return string
     */
    public function constructorWithDataReplacerSyntax(bool $callParent): string
    {
        $callParent = $callParent ? '        parent::__construct($actualData);' . "\n\n" : null;

        $s = '    public function __construct(array $actualData)' . "\n";
        $s .= '    {' . "\n";
        $s .= $callParent;
        $s .= '        $classVars = \get_class_vars(__CLASS__);' . "\n\n";
        $s .= '        foreach ($classVars as $k => $v)' . "\n";
        $s .= '            $this->$k = $actualData[$k] ?? null;' . "\n";
        $s .= '    }' . "\n\n";

        return $s;
    }

    /**
     * Get constructor with call parent syntax
     *
     * @return string
     */
    public function constructorWithCallParentSyntax()
    {
        $s = '    public function __construct(array $actualData)' . "\n";
        $s .= '    {' . "\n";
        $s .= '        parent::__construct($actualData);' . "\n";
        $s .= '    }' . "\n\n";

        return $s;
    }
}
