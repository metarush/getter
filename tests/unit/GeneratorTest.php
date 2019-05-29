<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
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
            'bool',
            'array'
        ];

        $valid = $this->generator->validType('foo');
        $this->assertFalse($valid);

        foreach ($validTypes as $type) {
            $valid = $this->generator->validType($type);
            $this->assertTrue($valid);
        }
    }

    public function testGeneratedGetProperty()
    {
        $expected = 'public function getFoo(): string{    return $this->foo;}';

        $s = $this->generator->generatedGetProperty('foo', 'string');
        // convert string to one line for easy testing
        $actual = \str_replace("\n", '', $s);

        $this->assertEquals($expected, $actual);
    }

    public function testGeneratedField()
    {
        $expected = 'private $foo;' . "\n";
        $actual = $this->generator->generatedField('foo');

        $this->assertEquals($expected, $actual);
    }
}
