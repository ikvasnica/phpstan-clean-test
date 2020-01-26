# PHPStan Clean Test rules

- [PHPStan](https://github.com/phpstan/phpstan)
- [PHPStan-PHPUnit extension](https://github.com/phpstan/phpstan-phpunit)

This extension provides highly opinionated and strict rules for test cases for the PHPStan static analysis tool.

## Installation

Run

```
$ composer require --dev ikvasnica/phpstan-clean-test
```

## Usage

All of the [rules](https://github.com/ikvasnica/phpstan-clean-test#rules) provided by this library are included in [`rules.neon`](rules.neon).

When you are using [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer), `rules.neon` will be automatically included.

Otherwise you need to include `rules.neon` in your `phpstan.neon`:

```neon
includes:
	- vendor/ikvasnica/phpstan-clean-test/rules.neon
```

:bulb: You probably want to use these rules on top of the rules provided by:

* [`phpstan/phpstan`](https://github.com/phpstan/phpstan)
* [`phpstan/phpstan-deprecation-rules`](https://github.com/phpstan/phpstan-deprecation-rules)
* [`phpstan/phpstan-strict-rules`](https://github.com/phpstan/phpstan-strict-rules)
* [`ergebnis/phpstan-rules`](https://github.com/ergebnis/phpstan-rules)
* [`phpstan/phpstan-phpunit`](https://github.com/phpstan/phpstan-phpunit)

## Rules

This package provides the following rules for use with [`phpstan/phpstan`](https://github.com/phpstan/phpstan):

* [`ikvasnica\PHPStan\Rules\UnitExtendsFromTestCaseRule`](#unitextendsfromtestcaserule)

### `UnitExtendsFromTestCaseRule`

**What it does:**

This rule forces you to extend only from allowed classes in unit tests (default: `PHPUnit\Framework\TestCase`).

**Why it is useful:**

It prevents developers i.e. from using a dependency injection container in unit tests (`$this->getContainer()`) and other tools from integration/functional tests.

#### Defaults

By default, this rule detects unit tests by checking the namespace (it must contain the string `Unit`) and the class name ending (it must end with the string `Test`).

The following classes are allowed to be extended:

* [`PHPUnit\Framework\TestCase`](https://github.com/sebastianbergmann/phpunit/blob/7.5.2/src/Framework/TestCase.php)


#### Allowing classes to be extended

If you want to allow additional classes to be extended, you can set the `classesAllowedToBeExtendedInTests` parameter to a list of class names:

```neon
parameters:
    ikvasnica:
        classesAllowedToBeExtendedInTests:
	    - PHPUnit\Framework\TestCase
            - MyNamespace\AbstractTest
```

#### Detecting unit tests namespace
If you want to change the namespace string check described above, you can set your own string to be checked in the `unitTestNamespaceContainsString` parameter:

```neon
parameters:
    ikvasnica:
        unitTestNamespaceContainsString: CustomTestPath
```

## TODO
- [ ] Implement Dependabot.com
- [ ] Add CI checks
- [ ] Add code coverage checks
