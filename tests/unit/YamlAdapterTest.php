<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class YamlAdapterTest extends TestCase
{
    private $cfg;
    private $fileGenerator;

    public function setUp(): void
    {
        $this->cfg = new \MetaRush\Getter\Config;

        $syntaxGenerator = new \MetaRush\Getter\SyntaxGenerator;

        $this->fileGenerator = new \MetaRush\Getter\Adapters\Yaml($this->cfg, $syntaxGenerator);
    }

    public function testGenerateClassFile()
    {
        $location = __DIR__ . '/samples/';
        $yamlFile = $location . 'sample.yaml';

        $this->cfg->setClassName('FooYaml');
        $this->cfg->setLocation($location);

        $this->fileGenerator->generate($yamlFile);

        $this->assertFileExists($location . 'FooYaml.php');

        \unlink($location . 'FooYaml.php');
    }
}
