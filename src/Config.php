<?php

declare(strict_types=1);

namespace MetaRush\Getter;

class Config
{
    /**
     * Type of adapter to use
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
     * Optional name of class to extend
     *
     * @var string
     */
    private $extendedClass;

    /**
     * Where to store the generate class
     *
     * @var string
     */
    private $location;

    /**
     * Array of data to generated from
     *
     * @var array
     */
    private $data;

    /**
     * Namespace of the generated class
     *
     * @var string
     */
    private $namespace;

    /**
     * Convert data values to dummy data. Useful for hiding sensitive data that can later be repopulated on runtime.
     *
     * @var bool
     */
    private $dummifyValues;

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

    public function getExtendedClass(): ?string
    {
        return $this->extendedClass;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getDummifyValues(): ?bool
    {
        return $this->dummifyValues;
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

    public function setExtendedClass(?string $extendedClass)
    {
        $this->extendedClass = $extendedClass;
        return $this;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function setNamespace(?string $namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function setDummifyValues(bool $dummifyValues)
    {
        $this->dummifyValues = $dummifyValues;
        return $this;
    }
}
