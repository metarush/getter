<?php

declare(strict_types=1);

namespace MetaRush\Getter\Adapters;

class Yaml extends \MetaRush\Getter\AbstractFileGenerator
{

    public function generate(string $className, string $yamlFile, string $location): void
    {
        $data = \Symfony\Component\Yaml\Yaml::parseFile($yamlFile);

        $this->generateClassFile($className, $data, $location);
    }
}
