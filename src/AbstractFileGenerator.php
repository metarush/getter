<?php

declare(strict_types=1);

namespace MetaRush\Getter;

abstract class AbstractFileGenerator
{
    private $syntaxGenerator;

    public function __construct(SyntaxGenerator $syntaxGenerator)
    {
        $this->syntaxGenerator = $syntaxGenerator;
    }

    /**
     * Generate class file
     *
     * @param string $className name of class to generate
     * @param array $data PHP data to generated from
     * @param string $location where to save generated class file
     * @return void
     */
    protected function generateClassFile(string $className, array $data, string $location): void
    {
        $header = "<?php\n\ndeclare(strict_types=1);\n\n";

        $classSyntax = $this->syntaxGenerator->classSyntax($className, $data);

        \file_put_contents($location . $className . '.php', $header . $classSyntax);
    }
}
