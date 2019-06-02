<?php

declare(strict_types=1);

namespace MetaRush\Getter\Adapters;

use MetaRush\Getter;

class Yaml extends Getter\AbstractFileGenerator
{

    public function __construct(Getter\SyntaxGenerator $syntaxGenerator)
    {
        parent::__construct($syntaxGenerator);
    }

    /**
     * Generate class file
     *
     * @param string $className
     * @param string $yamlFile
     * @param string $location Where to save the generated file
     * @return void
     */
    public function generate(string $className, string $yamlFile, string $location): void
    {
        $data = \Symfony\Component\Yaml\Yaml::parseFile($yamlFile);

        $this->generateClassFile($className, $data, $location);
    }
}
