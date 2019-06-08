# metarush/getter

Generate a class with getter methods from a `yaml` or `env` file.
This is useful if you want to access variables in `yaml`/`env` files as PHP getter methods.

## Install

Install via composer as `metarush/getter`

## Sample usage

The following will generate a class named `MyNewClass` from contents of `foo/sample.yaml` using `yaml` adapter.
The generated class will be saved in `foo/` folder.

### via CLI

`vendor/metarush/getter/bin/generate yaml MyNewClass foo/sample.yaml foo/ MyNamespace OptionalClassToExtend`

### via PHP script

```php
(new \MetaRush\Getter\Generator)
    ->setAdapter('yaml')
    ->setClassName('MyNewClass')
    ->setLocation('foo/')
    ->setSourceFile('foo/sample.yaml')
    ->setNamespace('MyNamespace')
    ->setExtendedClass('OptionalClassToExtend') // optional
    ->generate();
```

### Sample `yaml` content

E.g., located in `foo/sample.yaml`

    stringVar: 'foo'
    intVar: 9
    floatVar: 2.1
    boolVar: true
    arrayVar:
      - foo
      - 1.3

### Sample generated class

E.g., located in `foo/MyNewClass.php`

```php
<?php

declare(strict_types=1);

namespace MyNamespace;

class MyNewClass extends OptionalClassToExtend
{
    private $stringVar = 'foo';
    private $intVar = 9;
    private $floatVar = 2.1;
    private $boolVar = true;
    private $arrayVar = ['foo', 1.3];

    public function getStringVar(): string
    {
        return $this->stringVar;
    }

    public function getIntVar(): int
    {
        return $this->intVar;
    }

    public function getFloatVar(): float
    {
        return $this->floatVar;
    }

    public function getBoolVar(): bool
    {
        return $this->boolVar;
    }

    public function getArrayVar(): array
    {
        return $this->arrayVar;
    }
}
```

## Supported types

- string
- int
- float
- bool
- array (not supported in `env` adapter)

## Adapters

- yaml
- env

## Dummify field values

If you like to dummify field values, use the `->setDummifyValues(true)` config method or add `dummify` as 7th parameter in the CLI script

### Why dummify?

It's useful for hiding sensitive data such as credentials.
They should not be hard-coded in the source code.
The field values of the generated classes with dummy data can later be repopulated (by your custom scripts) on runtime with actual values.
The generated class retains the data types even if the original values have been dummified.

If dummified, the generated field values are as follows:

```php
[
    'x', // string
    1, // int
    1.2, // float
    true, // true
    ['x'] // array
];
```