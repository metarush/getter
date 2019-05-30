<?php

declare(strict_types=1);

namespace MetaRush\Getter;

abstract class AbstractFileGenerator
{
    private $sG;

    public function __construct(SyntaxGenerator $sG)
    {
        $this->sG = $sG;
    }

    /**
     * Generate class file
     *
     * @param string $className
     * @param array $data
     * @param string|null $location
     * @return void
     */
    public function generateClassFile(string $className, array $data, ?string $location = null): void
    {
        $header = "<?php\n\ndeclare(strict_types=1);\n\n";

        $classSyntax = $this->sG->classSyntax($className, $data);

        \file_put_contents($location . $className . '.php', $header . $classSyntax);
    }

    abstract public function data(): array;
}
