<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class YamlAdapterTest extends TestCase
{
    private $fG;

    public function setUp(): void
    {
        $this->fG = new \MetaRush\Getter\Adapters\Yaml;
    }

    public function testGenerateClassFile()
    {
        $location = __DIR__ . '/';
        $yamlFile = $location . 'sample.yaml';

        $this->fG->generate('Foo', $yamlFile, $location);

        $this->assertFileExists($location . 'Foo.php');

        \unlink($location . 'Foo.php');
    }
}
