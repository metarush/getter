<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class SyntaxGeneratorTest extends TestCase
{
    private $sG;
    private $cfg;

    public function setUp(): void
    {
        $this->cfg = new \MetaRush\Getter\Config;

        $this->sG = new \MetaRush\Getter\SyntaxGenerator($this->cfg);
    }

    public function testValidType()
    {
        $validTypes = [
            'string',
            'int',
            'float',
            'bool',
            'array',
        ];

        $valid = $this->sG->validType('foo');
        $this->assertFalse($valid);

        foreach ($validTypes as $type) {
            $valid = $this->sG->validType($type);
            $this->assertTrue($valid);
        }
    }

    public function testPropertySyntax()
    {
        $expected = '    public function getFoo(): string    {        return $this->foo;    }';

        $s = $this->sG->propertySyntax('foo', 'string');
        // convert string to one line for easy testing
        $actual = \str_replace("\n", '', $s);

        $this->assertEquals($expected, $actual);

        // ------------------------------------------------

        $this->expectException('Exception');
        $s = $this->sG->propertySyntax('foo', 'zstring');
    }

    public function testFieldSyntax()
    {
        // test string
        $expected = "    private \$foo = 'bar';\n";
        $actual = $this->sG->fieldSyntax('foo', 'bar');
        $this->assertEquals($expected, $actual);

        // test int
        $expected = "    private \$foo = 9;\n";
        $actual = $this->sG->fieldSyntax('foo', 9);
        $this->assertEquals($expected, $actual);

        // test float
        $expected = "    private \$foo = 1.2;\n";
        $actual = $this->sG->fieldSyntax('foo', 1.2);
        $this->assertEquals($expected, $actual);

        // test bool
        $expected = "    private \$foo = false;\n";
        $actual = $this->sG->fieldSyntax('foo', false);
        $this->assertEquals($expected, $actual);

        // tets array
        $expected = "    private \$foo = [0 => 1, 1 => 2, 2 => 3];\n";
        $actual = $this->sG->fieldSyntax('foo', [1, 2, 3]);
        $this->assertEquals($expected, $actual);
    }

    public function testGetType()
    {
        $a = [
            'string' => 'foo',
            'int'    => 9,
            'float'  => 1.2,
            'bool'   => true,
            'array'  => [1, 2],
        ];

        foreach ($a as $k => $v) {
            $expected = $k;
            $actual = $this->sG->getType($v);
            $this->assertEquals($expected, $actual);
        }
    }

    public function testClassSyntax()
    {
        $data = [
            'stringVar' => 'foo',
            'intVar'    => 9,
            'floatVar'  => 1.2,
            'boolVar'   => true,
            'arrayVar'  => [1, 2],
        ];

        $this->cfg->setClassName('MyClass');
        $this->cfg->setData($data);

        $expected = 'class MyClass{    private $stringVar = \'foo\';    private $intVar = 9;    private $floatVar = 1.2;    private $boolVar = true;    private $arrayVar = [0 => 1, 1 => 2];    public function getStringVar(): string    {        return $this->stringVar;    }    public function getIntVar(): int    {        return $this->intVar;    }    public function getFloatVar(): float    {        return $this->floatVar;    }    public function getBoolVar(): bool    {        return $this->boolVar;    }    public function getArrayVar(): array    {        return $this->arrayVar;    }}';

        $s = $this->sG->classSyntax();
        $actual = \str_replace("\n", '', $s);

        $this->assertEquals($expected, $actual);
    }

    public function testClassSyntaxWithExtendedClass()
    {
        $data = [
            'stringVar' => 'foo',
        ];

        $this->cfg->setClassName('MyClass');
        $this->cfg->setData($data);
        $this->cfg->setExtendedClass('BaseClass');

        $expected = 'class MyClass extends BaseClass{    private $stringVar = \'foo\';    public function getStringVar(): string    {        return $this->stringVar;    }}';

        $s = $this->sG->classSyntax();
        $actual = \str_replace("\n", '', $s);

        $this->assertEquals($expected, $actual);
    }

    public function testVarValueSyntax()
    {
        $value = [1, 'foo', true, [2.5, ['bar', false, 'qux']]];

        $expected = "[0 => 1, 1 => 'foo', 2 => true, 3 => [0 => 2.5, 1 => [0 => 'bar', 1 => false, 2 => 'qux']]]";
        $actual = $this->sG->varValueSyntax($value);

        $this->assertEquals($expected, $actual);
    }

    public function testSimpleArraySyntax()
    {
        $array = ['foo', false, 'bar', 1];
        $expected = "[0 => 'foo', 1 => false, 2 => 'bar', 3 => 1]";
        $actual = $this->sG->arraySyntax($array);

        $this->assertEquals($expected, $actual);
    }

    public function testAssociativeArraySyntax()
    {
        $array = [7 => 'foo', 8 => false, 9 => 'bar'];
        $expected = "[7 => 'foo', 8 => false, 9 => 'bar']";
        $actual = $this->sG->arraySyntax($array);

        $this->assertEquals($expected, $actual);
    }

    public function testDummifyValues()
    {
        $raw = [
            'foo',
            9,
            9.5,
            false,
            ['bar', 3.5]
        ];

        $dummified = [
            'x',
            1,
            1.2,
            true,
            ['x']
        ];

        foreach ($raw as $k => $v) {
            $actual = $this->sG->dummifyValue($v);
            $this->assertEquals($dummified[$k], $actual);
        }
    }

    public function testClassWithDummifiedValues()
    {
        $data = [
            'stringVar' => 'foo',
            'arrayVar'  => ['bar']
        ];

        $this->cfg->setClassName('MyClass');
        $this->cfg->setData($data);
        $this->cfg->setDummifyValues(true);

        $expected = 'class MyClass{    private $stringVar = \'x\';    private $arrayVar = [0 => \'x\'];    public function getStringVar(): string    {        return $this->stringVar;    }    public function getArrayVar(): array    {        return $this->arrayVar;    }}';

        $s = $this->sG->classSyntax();
        $actual = \str_replace("\n", '', $s);

        $this->assertEquals($expected, $actual);
    }

    public function testConstructorSntaxWithDataReplacer()
    {
        $expected = '    public function __construct(array $actualData)    {        $classVars = \get_class_vars(__CLASS__);        foreach ($classVars as $k => $v)            $this->$k = $actualData[$k] ?? null;    }';
        $s = $this->sG->constructorWithDataReplacerSyntax(false);
        $actual = \str_replace("\n", '', $s);
        $this->assertEquals($expected, $actual);

        $expected = '    public function __construct(array $actualData)    {        parent::__construct($actualData);        $classVars = \get_class_vars(__CLASS__);        foreach ($classVars as $k => $v)            $this->$k = $actualData[$k] ?? null;    }';
        $s = $this->sG->constructorWithDataReplacerSyntax(true);
        $actual = \str_replace("\n", '', $s);
        $this->assertEquals($expected, $actual);
    }

    public function testConstructorSntaxWithCallParent()
    {
        $expected = '    public function __construct(array $actualData)    {        parent::__construct($actualData);    }';

        $s = $this->sG->constructorWithCallParentSyntax();

        $actual = \str_replace("\n", '', $s);

        $this->assertEquals($expected, $actual);
    }

    public function testConstantSyntax()
    {
        // test string
        $expected = "    const foo = 'bar';\n";
        $actual = $this->sG->constantSyntax('foo', 'bar');
        $this->assertEquals($expected, $actual);

        // test int
        $expected = "    const foo = 9;\n";
        $actual = $this->sG->constantSyntax('foo', 9);
        $this->assertEquals($expected, $actual);

        // test float
        $expected = "    const foo = 1.2;\n";
        $actual = $this->sG->constantSyntax('foo', 1.2);
        $this->assertEquals($expected, $actual);

        // test bool
        $expected = "    const foo = false;\n";
        $actual = $this->sG->constantSyntax('foo', false);
        $this->assertEquals($expected, $actual);

        // tets array
        $expected = "    const foo = [0 => 1, 1 => [0 => 'bar', 1 => 1.2], 2 => 3];\n";
        $actual = $this->sG->constantSyntax('foo', [1, ['bar', 1.2], 3]);
        $this->assertEquals($expected, $actual);
    }
}