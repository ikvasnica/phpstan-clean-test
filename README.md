# PHPStan Clean Test rules

![Continuous Integration](https://github.com/ikvasnica/phpstan-clean-test/workflows/continuous-integration/badge.svg?event=push)

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

```yaml
# phpstan.neon
includes:
    - vendor/ikvasnica/phpstan-clean-test/rules.neon
```

## Rules

This package provides the following rules for use with [`phpstan/phpstan`](https://github.com/phpstan/phpstan):

* [`ikvasnica\PHPStan\Rules\UnitExtendsFromTestCaseRule`](#unitextendsfromtestcaserule)
* [`ikvasnica\PHPStan\Rules\DisallowSetupAndConstructorRule`](#disallowsetupandconstructorrule)

### `UnitExtendsFromTestCaseRule`

This rule forces you to extend only from allowed classes in unit tests (default: `PHPUnit\Framework\TestCase`).
It prevents developers i.e. from using a dependency injection container in unit tests (`$this->getContainer()`) and other tools from integration/functional tests.

:x:

```php
namespace ExampleTestCase\Unit;

class UnitExtendsInvalidTest extends \Dummy\FunctionalDummyTest {}
```
<br />

:white_check_mark:

```php
namespace ExampleTestCase\Unit;

class UnitExtendsUnitTest extends \PHPUnit\Framework\TestCase {}
```

#### Defaults

- By default, this rule detects unit tests by checking the namespace (it must contain the string `Unit`) and the class name ending (it must end with the string `Test`).

- The following class is allowed to be extended: `PHPUnit\Framework\TestCase`


#### Allowing classes to be extended

If you want to allow additional classes to be extended, you can set the `classesAllowedToBeExtendedInTests` parameter to a list of class names:

```yaml
parameters:
    ikvasnica:
        classesAllowedToBeExtendedInTests:
            - PHPUnit\Framework\TestCase
            - MyNamespace\AbstractTest
```

#### Detecting unit tests namespace
If you want to change the namespace string check described above, you can set your own string to be checked in the `unitTestNamespaceContainsString` parameter:

```yaml
parameters:
    ikvasnica:
        unitTestNamespaceContainsString: CustomTestPath
```

### `DisallowSetupAndConstructorRule`

Neither of methods `__construct` nor `setUp` can be declared in a unit test. You can set the unit tests namespace by using the same configuration like in [`UnitExtendsFromTestCaseRule`](#unitextendsfromtestcaserule).

:x:

```php
namespace ExampleTestCase\Unit;

class DisallowSetupConstructInvaliTest extends \PHPUnit\Framework\TestCase
{
    private $something;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->something = true;
    }

    public function testSomeThing(): void
    {
        $this->assertTrue($this->something);
    }
```

<br />
:white_check_mark:

```php
namespace ExampleTestCase\Unit;

class DisallowSetupConstructOkTest extends \PHPUnit\Framework\TestCase
{
    public function testSomeThing(): void
    {
        $this->assertTrue(true);
    }
}
```
## TODO
- [ ] Implement Dependabot.com
- [ ] Add code coverage checks
