<?php

declare(strict_types=1);

namespace MetaRush\Getter;

class FileGeneratorConfig
{
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

    /**
     * Array of data to generated from
     *
     * @var array
     */
    private $data;

    /**
     * Optional name of class to extend from
     *
     * @var string
     */
    private $extendedClass;

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getExtendedClass(): ?string
    {
        return $this->extendedClass;
    }

    public function setClassName(?string $className)
    {
        $this->className = $className;
        return $this;
    }

    public function setLocation(string $location)
    {
        $this->location = $location;
        return $this;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function setExtendedClass(?string $extendedClass)
    {
        $this->extendedClass = $extendedClass;
        return $this;
    }
}
