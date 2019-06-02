<?php

declare(strict_types=1);

namespace MetaRush\Getter;

class Generator extends Config
{

    /**
     * Generate class file
     *
     * @return void
     * @throws Exception
     */
    public function generate(): void
    {
        if (!$this->validAdapter($this->getAdapter()))
            throw new Exception('Invalid adapter ' . $this->getAdapter());

        $syntaxGenerator = new SyntaxGenerator;

        if ($this->getAdapter() === 'yaml') {
            $generator = new Adapters\Yaml($syntaxGenerator);
            $generator->generate($this->getClassName(), $this->getSourceFile(), $this->getLocation());
        } elseif ($this->getAdapter() === 'env') {
            $generator = new Adapters\Env($syntaxGenerator);
            $generator->generate($this->getClassName(), $this->getSourceFile(), $this->getLocation());
        }
    }

    /**
     * Check if $adapter is valid
     *
     * @param string $adapter
     * @return bool
     */
    private function validAdapter(string $adapter): bool
    {
        $adapters = [
            'yaml',
            'env'
        ];

        return \in_array($adapter, $adapters);
    }
}
