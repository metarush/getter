<?php

declare(strict_types=1);

namespace MetaRush\Getter;

abstract class AbstractFileGenerator
{
    /**
     *
     * @var FileGeneratorConfig
     */
    protected $cfg;
    private $syntaxGenerator;

    public function __construct(FileGeneratorConfig $cfg, SyntaxGenerator $syntaxGenerator)
    {
        $this->cfg = $cfg;
        $this->syntaxGenerator = $syntaxGenerator;
    }

    /**
     * Generate class file
     *
     * @return void
     */
    protected function generateClassFile(): void
    {
        $header = "<?php\n\ndeclare(strict_types=1);\n\n";

        $classSyntax = $this->syntaxGenerator->classSyntax($this->cfg->getClassName(), $this->cfg->getData(), $this->cfg->getExtendedClass());

        \file_put_contents($this->cfg->getLocation() . $this->cfg->getClassName() . '.php', $header . $classSyntax);
    }
}
