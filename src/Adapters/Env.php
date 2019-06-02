<?php

declare(strict_types=1);

namespace MetaRush\Getter\Adapters;

use MetaRush\Getter;

class Env extends Getter\AbstractFileGenerator
{

    public function __construct(Getter\SyntaxGenerator $syntaxGenerator)
    {
        parent::__construct($syntaxGenerator);
    }

    /**
     * Generate class file
     *
     * @param string $className
     * @param string $envFile
     * @param string $location Where to save the generated file
     * @return void
     */
    public function generate(string $className, string $envFile, string $location): void
    {
        $envContents = file_get_contents($envFile);

        $array = \M1\Env\Parser::parse($envContents);

        $this->generateClassFile($className, $array, $location);
    }
}
