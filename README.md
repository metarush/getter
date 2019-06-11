# metarush/getter

Generate a class with getter methods from a `yaml` or `env` file.
This is useful if you want to access variables in `yaml`/`env` files as PHP getter methods.

## Install

Install via composer as `metarush/getter`

## Sample usage

The following will generate a class named `MyNewClass` from contents of `foo/sample.yaml` using `yaml` adapter.
The generated class will be saved in `foo/` folder.

### via CLI

`vendor/metarush/getter/bin/generate -a yaml -c MyNewClass -s tests/unit/samples/sample.yaml -l foo/`

### via PHP script

```php
(new \MetaRush\Getter\Generator)
    ->setAdapter('yaml')
    ->setClassName('MyNewClass')
    ->setSourceFile('foo/sample.yaml')
    ->setLocation('foo/')
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

class MyNewClass
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

## Advanced usage

### Include namespace

If you want to include a namespace, use `->setNamespace('MyNamespace')` or via CLI `--namespace MyNamespace`

### Extend class

If you want to extend a class, use `->setExtendedClass('MyBaseClass')` or via CLI `--extendClass MyBaseClass`

### Dummify field values

If you want to dummify field values, use the `->setDummifyValues(true)` config method or via CLI `--dummify`

#### Why dummify?

It's useful for hiding sensitive data such as credentials.
Credentials should not be in the source code.
The field values of the generated classes with dummy data can later be repopulated (by your custom scripts) on runtime with actual values (see data replacer below).
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

### Data replacer for dummified values

If you want to dynamically change the values of the dummified data on runtime, use  `->setConstructorType(\MetaRush\Getter\Config::CONSTRUCTOR_DATA_REPLACER);` or via CLI `--dataReplacer`

You can then call the generated class and inject an array of replacement values on runtime:

```php

// $replacementValues should contain key value pair that matches the dummified data

$newClass = new MyNewClass($replacementValues);
```

### Call parent

If you want to call a parent class, use `->setConstructorType(\MetaRush\Getter\Config::CONSTRUCTOR_CALL_PARENT)` or via CLI `--callParent`

### Call parent and use data replacer

If you want to call a parent class and also use data replacer, use `->setConstructorType(\MetaRush\Getter\Config::CONSTRUCTOR_BOTH)` or via CLI `--callParent --dataReplacer`

Note: You should only call `->setConstructorType()` once

### Generate constants

If you want to generate the key/value pairs as constants, use `->setGenerateAsConstants(true)` or via CLI `--constants`.