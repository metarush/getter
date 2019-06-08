<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class EnvAdapterTest extends TestCase
{
    private $cfg;
    private $fileGenerator;

    public function setUp(): void
    {
        $this->cfg = new \MetaRush\Getter\Config;

        $syntaxGenerator = new \MetaRush\Getter\SyntaxGenerator($this->cfg);

        $this->fileGenerator = new \MetaRush\Getter\Adapters\Env($this->cfg, $syntaxGenerator);
    }

    public function testGenerateClassFile()
    {
        $location = __DIR__ . '/samples/';
        $envFile = $location . 'sample.env';

        $this->cfg->setClassName('FooEnv');
        $this->cfg->setLocation($location);

        $this->fileGenerator->generate($envFile);

        $this->assertFileExists($location . 'FooEnv.php');

        \unlink($location . 'FooEnv.php');
    }
}
