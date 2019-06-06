<?php

declare(strict_types=1);

namespace MetaRush\Getter\Adapters;

use MetaRush\Getter;

class Env extends Getter\AbstractFileGenerator
{

    public function __construct(Getter\FileGeneratorConfig $cfg, Getter\SyntaxGenerator $syntaxGenerator)
    {
        parent::__construct($cfg, $syntaxGenerator);
    }

    /**
     * Generate class file
     *
     * @param string $envFile
     * @return void
     */
    public function generate(string $envFile): void
    {
        $envContents = \file_get_contents($envFile);

        $array = \M1\Env\Parser::parse($envContents);

        $this->cfg->setData($array);

        $this->generateClassFile();
    }
}
