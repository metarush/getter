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
}
