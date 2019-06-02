<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{

    public function setUp(): void
    {

    }

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

    public function testGenerateClassFromYamlEnvFile()
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
}
