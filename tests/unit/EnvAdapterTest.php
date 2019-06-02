<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class EnvAdapterTest extends TestCase
{
    private $fileGenerator;

    public function setUp(): void
    {
        $syntaxGenerator = new \MetaRush\Getter\SyntaxGenerator;

        $this->fileGenerator = new \MetaRush\Getter\Adapters\Env($syntaxGenerator);
    }

    public function testGenerateClassFile()
    {
        $location = __DIR__ . '/';
        $envFile = $location . 'sample.env';

        $this->fileGenerator->generate('FooEnv', $envFile, $location);

        $this->assertFileExists($location . 'FooEnv.php');

        \unlink($location . 'FooEnv.php');
    }
}
