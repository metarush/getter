<?php

declare(strict_types=1);

namespace MetaRush\Getter;

class Config
{
    /**
     *
     * @var string
     */
    private $adapter;

    /**
     * Source file where class will be generated from
     *
     * @var string
     */
    private $sourceFile;

    /**
     * Name of class to generate
     *
     * @var string
     */
    private $className;

    /**
     * Where to store the generate class
     *
     * @var string
     */
    private $location;

    public function getAdapter(): string
    {
        return $this->adapter;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getSourceFile(): string
    {
        return $this->sourceFile;
    }

    public function setAdapter(string $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function setClassName(string $className)
    {
        $this->className = $className;
        return $this;
    }

    public function setLocation(string $location)
    {
        $this->location = $location;
        return $this;
    }

    public function setSourceFile(string $sourceFile)
    {
        $this->sourceFile = $sourceFile;
        return $this;
    }
}
