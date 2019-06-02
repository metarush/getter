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

    public function generate(string $className, string $envFile, string $location): void
    {
        $envContents = file_get_contents($envFile);

        $array = \M1\Env\Parser::parse($envContents);

        $this->generateClassFile($className, $array, $location);
    }
}
