<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class YamlAdapterTest extends TestCase
{
    private $fileGenerator;

    public function setUp(): void
    {
        $syntaxGenerator = new \MetaRush\Getter\SyntaxGenerator;

        $this->fileGenerator = new \MetaRush\Getter\Adapters\Yaml($syntaxGenerator);
    }

    public function testGenerateClassFile()
    {
        $location = __DIR__ . '/';
        $yamlFile = $location . 'sample.yaml';

        $this->fileGenerator->generate('Foo', $yamlFile, $location);

        $this->assertFileExists($location . 'Foo.php');

        \unlink($location . 'Foo.php');
    }
}
