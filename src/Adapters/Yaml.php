<?php

declare(strict_types=1);

namespace MetaRush\Getter\Adapters;

use MetaRush\Getter;

class Yaml extends Getter\AbstractFileGenerator
{

    public function __construct(Getter\Config $cfg, Getter\SyntaxGenerator $syntaxGenerator)
    {
        parent::__construct($cfg, $syntaxGenerator);
    }

    /**
     * Generate class file
     *
     * @param string $yamlFile
     * @return void
     */
    public function generate(string $yamlFile): void
    {
        $array = \Symfony\Component\Yaml\Yaml::parseFile($yamlFile);

        $this->cfg->setData($array);

        $this->generateClassFile();
    }
}
