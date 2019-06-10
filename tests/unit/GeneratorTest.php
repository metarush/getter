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

    public function testGenerateWithDummifiedData()
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

    public function testGenerateWithCallParentConstructor()
    {
        $location = __DIR__ . '/samples/';
        $className = 'ConstructorTest';

        (new \MetaRush\Getter\Generator)
            ->setAdapter('yaml')
            ->setClassName($className)
            ->setLocation($location)
            ->setSourceFile($location . 'sample.yaml')
            ->setConstructorType(MetaRush\Getter\Config::CONSTRUCTOR_CALL_PARENT)
            ->generate();

        $this->assertFileExists($location . $className . '.php');

        $classContent = \file_get_contents($location . $className . '.php');

        $this->assertStringContainsString('public function __construct(array $actualData)', $classContent);
        $this->assertStringContainsString('parent::__construct($actualData);', $classContent);

        \unlink($location . $className . '.php');
    }

    public function testGenerateWithDataReplacerConstructor()
    {
        $location = __DIR__ . '/samples/';
        $className = 'ConstructorTest';

        (new \MetaRush\Getter\Generator)
            ->setAdapter('yaml')
            ->setClassName($className)
            ->setLocation($location)
            ->setSourceFile($location . 'sample.yaml')
            ->setConstructorType(MetaRush\Getter\Config::CONSTRUCTOR_DATA_REPLACER)
            ->generate();

        $this->assertFileExists($location . $className . '.php');

        $classContent = \file_get_contents($location . $className . '.php');

        $this->assertStringContainsString('public function __construct(array $actualData)', $classContent);
        $this->assertStringContainsString('$classVars = \get_class_vars(__CLASS__);', $classContent);
        $this->assertStringContainsString('foreach ($classVars as $k => $v)', $classContent);
        $this->assertStringContainsString('$this->$k = $actualData[$k] ?? null;', $classContent);


        \unlink($location . $className . '.php');
    }

    public function testGenerateWithCallParendAndDataReplacerConstructor()
    {
        $location = __DIR__ . '/samples/';
        $className = 'ConstructorTest';

        (new \MetaRush\Getter\Generator)
            ->setAdapter('yaml')
            ->setClassName($className)
            ->setLocation($location)
            ->setSourceFile($location . 'sample.yaml')
            ->setConstructorType(MetaRush\Getter\Config::CONSTRUCTOR_BOTH)
            ->generate();

        $this->assertFileExists($location . $className . '.php');

        $classContent = \file_get_contents($location . $className . '.php');

        $this->assertStringContainsString('public function __construct(array $actualData)', $classContent);
        $this->assertStringContainsString('parent::__construct($actualData);', $classContent);
        $this->assertStringContainsString('$classVars = \get_class_vars(__CLASS__);', $classContent);
        $this->assertStringContainsString('foreach ($classVars as $k => $v)', $classContent);
        $this->assertStringContainsString('$this->$k = $actualData[$k] ?? null;', $classContent);

        \unlink($location . $className . '.php');
    }
}
