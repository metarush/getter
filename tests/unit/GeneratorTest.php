<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{

    public function testGenerateClassFromYamlFile()
    {
        $location = __DIR__ . '/samples/';
        $className = 'YamlTest';

        (new \MetaRush\Getter\Generator)
            ->setAdapter('yaml')
            ->setClassName($className)
            ->setLocation($location)
            ->setSourceFile($location . 'sample.yaml')
            ->generate();

        $this->assertFileExists($location . $className . '.php');

        \unlink($location . $className . '.php');
    }

    public function testGenerateClassFromEnvFile()
    {
        $location = __DIR__ . '/samples/';
        $className = 'EnvTest';

        (new \MetaRush\Getter\Generator)
            ->setAdapter('env')
            ->setClassName($className)
            ->setLocation($location)
            ->setSourceFile($location . 'sample.env')
            ->generate();

        $this->assertFileExists($location . $className . '.php');

        \unlink($location . $className . '.php');
    }

    public function testExeption()
    {
        $this->expectException('Exception');

        $location = __DIR__ . '/samples/';
        $className = 'EnvTest';

        (new \MetaRush\Getter\Generator)
            ->setAdapter('foo')
            ->setClassName($className)
            ->setLocation($location)
            ->setSourceFile($location . 'sample.env')
            ->generate();
    }

    public function testGenerateWithExtendedClass()
    {
        $location = __DIR__ . '/samples/';
        $className = 'EnvTest';

        (new \MetaRush\Getter\Generator)
            ->setAdapter('yaml')
            ->setClassName($className)
            ->setExtendedClass('ExtendMe')
            ->setLocation($location)
            ->setSourceFile($location . 'sample.yaml')
            ->generate();

        $this->assertFileExists($location . $className . '.php');

        \unlink($location . $className . '.php');
    }

    public function testGenerateWithNamespace()
    {
        $location = __DIR__ . '/samples/';
        $className = 'EnvTest';

        (new \MetaRush\Getter\Generator)
            ->setAdapter('yaml')
            ->setClassName($className)
            ->setExtendedClass('ExtendMe')
            ->setNamespace('Foo\Bar')
            ->setLocation($location)
            ->setSourceFile($location . 'sample.yaml')
            ->generate();

        $this->assertFileExists($location . $className . '.php');

        $classContent = \file_get_contents($location . $className . '.php');

        $this->assertStringContainsString('namespace Foo\Bar;', $classContent);

        \unlink($location . $className . '.php');
    }

    public function testGeneratedWithDummyData()
    {
        $location = __DIR__ . '/samples/';
        $className = 'DummyTest';

        (new \MetaRush\Getter\Generator)
            ->setAdapter('yaml')
            ->setClassName($className)
            ->setExtendedClass('ExtendMe')
            ->setNamespace('Foo\Bar')
            ->setLocation($location)
            ->setSourceFile($location . 'sample.yaml')
            ->setDummifyValues(true)
            ->generate();

        $this->assertFileExists($location . $className . '.php');

        $classContent = \file_get_contents($location . $className . '.php');

        $expectedValues = [
            '$stringVar = \'x\'',
            '$intVar = 1;',
            '$floatVar = 1.2',
            '$boolVar = true;',
            '$arrayVar = [\'x\'];'
        ];

        foreach ($expectedValues as $v)
            $this->assertStringContainsString($v, $classContent);

        \unlink($location . $className . '.php');
    }
}
