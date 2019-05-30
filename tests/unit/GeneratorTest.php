<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    private $generator;

    public function setUp(): void
    {
        $this->generator = new \MetaRush\Getter\Generator;
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

        $valid = $this->generator->validType('foo');
        $this->assertFalse($valid);

        foreach ($validTypes as $type) {
            $valid = $this->generator->validType($type);
            $this->assertTrue($valid);
        }
    }

    public function testPropertySyntax()
    {
        $expected = '    public function getFoo(): string    {        return $this->foo;    }';

        $s = $this->generator->propertySyntax('foo', 'string');
        // convert string to one line for easy testing
        $actual = \str_replace("\n", '', $s);

        $this->assertEquals($expected, $actual);

        // ------------------------------------------------

        $this->expectException('\InvalidArgumentException');
        $s = $this->generator->propertySyntax('foo', 'zstring');
    }

    public function testFieldSyntax()
    {
        // test string
        $expected = "    private \$foo = 'bar';\n";
        $actual = $this->generator->fieldSyntax('foo', 'bar');

        // test int
        $expected = "    private \$foo = 9;\n";
        $actual = $this->generator->fieldSyntax('foo', 9);

        // test float
        $expected = "    private \$foo = 1.2;\n";
        $actual = $this->generator->fieldSyntax('foo', 1.2);

        // test bool
        $expected = "    private \$foo = false;\n";
        $actual = $this->generator->fieldSyntax('foo', false);

        // tets array
        $expected = "    private \$foo = [1, 2, 3];\n";
        $actual = $this->generator->fieldSyntax('foo', [1, 2, 3]);

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
            $actual = $this->generator->getType($v);
            $this->assertEquals($expected, $actual);
        }
    }

    public function testClassSyntax()
    {
        $a = [
            'stringVar' => 'foo',
            'intVar'    => 9,
            'floatVar'  => 1.2,
            'boolVar'   => true,
            'arrayVar'  => [1, 2],
        ];

        $expected = 'class MyClass{    private $stringVar = \'foo\';    private $intVar = 9;    private $floatVar = 1.2;    private $boolVar = true;    private $arrayVar = [1, 2];    public function getStringVar(): string    {        return $this->stringVar;    }    public function getIntVar(): int    {        return $this->intVar;    }    public function getFloatVar(): float    {        return $this->floatVar;    }    public function getBoolVar(): bool    {        return $this->boolVar;    }    public function getArrayVar(): array    {        return $this->arrayVar;    }}';

        $s = $this->generator->classSyntax('MyClass', $a);
        $actual = \str_replace("\n", '', $s);
        $this->assertEquals($expected, $actual);
    }

    public function testVarValueSyntax()
    {
        $value = [1, 'foo', true, [2.5, ['bar', false, 'qux']]];

        $expected = "[1, 'foo', true, [2.5, ['bar', false, 'qux']]]";
        $actual = $this->generator->varValueSyntax($value);

        $this->assertEquals($expected, $actual);
    }

    public function testArraySyntax()
    {
        $array = ['foo', false, 'bar', 1];
        $expected = "['foo', false, 'bar', 1]";
        $actual = $this->generator->arraySyntax($array);

        $this->assertEquals($expected, $actual);
    }

    public function testGenerateClassFile()
    {
        $data = [
            'stringVar' => 'foo',
            'intVar'    => 9,
            'floatVar'  => 1.2,
            'boolVar'   => true,
            'arrayVar'  => [1, 'bar', [3, 4.2]],
        ];

        $location = __DIR__ . '/';

        $this->generator->generateClassFile('Foo', $data, $location);

        $this->assertFileExists($location . 'Foo.php');

        \unlink($location . 'Foo.php');
    }
}
