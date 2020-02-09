# PHPStan Clean Test rules

![Continuous Integration](https://github.com/ikvasnica/phpstan-clean-test/workflows/continuous-integration/badge.svg?event=push)
[![Coverage Status](https://coveralls.io/repos/github/ikvasnica/phpstan-clean-test/badge.svg?branch=master)](https://coveralls.io/github/ikvasnica/phpstan-clean-test?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/aa40e911787049d3bc9f987ec1809f5b)](https://www.codacy.com/manual/ikvasnica/phpstan-clean-test?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ikvasnica/phpstan-clean-test&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/ikvasnica/phpstan-clean-test/version)](https://packagist.org/packages/ikvasnica/phpstan-clean-test)
[![License](https://poser.pugx.org/ikvasnica/phpstan-clean-test/license)](https://packagist.org/packages/ikvasnica/phpstan-clean-test)

-   [PHPStan](https://github.com/phpstan/phpstan)
-   [PHPStan-PHPUnit extension](https://github.com/phpstan/phpstan-phpunit)

This extension provides highly opinionated and strict rules for test cases for the PHPStan static analysis tool.

## Installation

Run

```shell
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
-   [`ikvasnica\PHPStan\Rules\UnitExtendsFromTestCaseRule`](#unitextendsfromtestcaserule)
-   [`ikvasnica\PHPStan\Rules\DisallowSetupAndConstructorRule`](#disallowsetupandconstructorrule)
-   [`ikvasnica\PHPStan\Rules\AssertSameOverAssertEqualsRule`](#assertsameoverassertequalsrule)
-   [`ikvasnica\PHPStan\Rules\StaticAssertOverThisAndStaticRule`](#staticassertoverthisandstaticrule)

### `UnitExtendsFromTestCaseRule`

This rule forces you to extend only from allowed classes in unit tests (default: `PHPUnit\Framework\TestCase`).

**Why:**
1. It prevents developers i.e. from using a dependency injection container in unit tests (`$this->getContainer()`) and other tools from integration/functional tests.
2. You should extend only from a class when a child class satisfies the "is a" relationship. That said, if you need only a subset of a parent's functionality, you should use composition over inheritance (i.e. by traits or helpers).

:x:

```php
// tests/ExampleTestCase/Unit/UnitExtendsInvalidTest.php
namespace ExampleTestCase\Unit;

final class UnitExtendsInvalidTest extends \Dummy\FunctionalDummyTest {}
```
<br />

:white_check_mark:

```php
// tests/ExampleTestCase/Unit/UnitExtendsUnitTest.php
namespace ExampleTestCase\Unit;

final class UnitExtendsUnitTest extends \PHPUnit\Framework\TestCase {}
```

#### Defaults

-   By default, this rule detects unit tests by checking the namespace (it must contain the string `Unit`) and the class name ending (it must end with the string `Test`).
-   The following class is allowed to be extended: `PHPUnit\Framework\TestCase`

#### Allowing classes to be extended

If you want to allow additional classes to be extended, you can add it to the `classesAllowedToBeExtendedInTests` parameter to a list of class names.

#### Detecting unit tests namespace
If you want to change the namespace string check described above, you can set your own string to be checked in the `unitTestNamespaceContainsString` parameter.

```yaml
# phpstan.neon
parameters:
    ikvasnica:
        classesAllowedToBeExtendedInTests:
          - MyNamespace\AbstractTest
        unitTestNamespaceContainsString: CustomTestPath
```

### `DisallowSetupAndConstructorRule`

Neither of methods `__construct` nor `setUp` can be declared in a unit test.

**Why:**
Each test scenario should create its dependencies on its own. Method `setUp` is useful for setting up i.e. database transaction in a functional test. In a unit test, you should put all the preparation into a testing method or a data provider itself. It increases readability and clearly shows the code intention.

#### Detecting unit tests namespace
If you want to change the namespace string check described above, you can set your own string to be checked in the `unitTestNamespaceContainsString` parameter.

#### Allowing setUp() method
If you really want to use the setUp() method, you can whitelist it by setting the parameter `allowSetupInUnitTests` to `true`.

```yaml
# phpstan.neon
parameters:
    ikvasnica:
        unitTestNamespaceContainsString: CustomTestPath
        allowSetupInUnitTests: true
```

:x:

```php
// tests/ExampleTestCase/Unit/DisallowSetupConstructInvaliTest.php
namespace ExampleTestCase\Unit;

final class DisallowSetupConstructInvaliTest extends \PHPUnit\Framework\TestCase
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
}
```

<br />
:white_check_mark:

```php
// tests/ExampleTestCase/Unit/DisallowSetupConstructOkTest.php
namespace ExampleTestCase\Unit;

final class DisallowSetupConstructOkTest extends \PHPUnit\Framework\TestCase
{
    public function testSomeThing(): void
    {
        $this->assertTrue(true);
    }
}
```

### `AssertSameOverAssertEqualsRule`
Calling `assertEquals` in tests is forbidden in favor of `assertSame`.

**Why:**
When using `assertEquals`, data types are not considered. On the other hand, `assertSame` checks whether two variables are of the same type and references the same object. Therefore, `assertEquals` can be valid when comparing objects or arrays, but not scalar values.

Using `assertEquals` with scalar values might lead to an unexpected behaviour (e.g. `assertEquals(null, '')` evaluates to `true`, whereas `assertSame(null, '')` evaluates to `false`).

:x:

```php
// tests/ExampleTestCase/Unit/InvalidAssertEqualsUses.php
$booleanValue = false;
$exception = new Exception('A bad thing has happened.');

$this->assertEquals(true, $booleanValue);
$this->assertEquals('exception message', (string) $exception);
```

<br />
:white_check_mark:

```php
// tests/ExampleTestCase/Unit/ValidAsserts.php
$booleanValue = false;
$exception = new Exception('A bad thing has happened.');
$emptyArray = [];

$this->assertTrue($booleanValue);
$this->assertSame('exception message', (string) $exception);

$this->assertEquals([], $emptyArray);
```

### `StaticAssertOverThisAndStaticRule`
Calling `$this->assert*`, `self::assert*` or `static::assert*` in tests is forbidden in favor of `PHPUnit\Framework\Assert::assert*`.

**Why:**
When you use PHPUnit, your test cases extend from `\PHPUnit\Framework\TestCase`. Assert methods are declared as static there, therefore it does not make sense to call them dynamically.
Using `static::assert*` is discouraged, because it is a misuse of inheritance and assertion methods are more like a helper's methods.

:x:

```php
// tests/ExampleTestCase/Unit/InvalidAssertUsage.php
namespace ExampleTestCase;

final class InvalidAssertUsageTest extends \PHPUnit\Framework\TestCase
{
    public function dummyTest(): void
    {
        // will fail
        $this->assertSame(5, 5);
        $this->assertTrue(false);
        self::assertArrayHasKey(5, [5]);
        static::assertCount(0, []);
        \ExampleTestCase\StaticAssertOverThisAndStaticRule::assertTrue(true);
        InvalidAssertUsageTest::assertTrue(true);
    }
}
```

<br />
:white_check_mark:

```php
// tests/ExampleTestCase/Unit/ValidAssertsUsage.php
namespace ExampleTestCase;

use PHPUnit\Framework\Assert;

final class ValidAssertUsageTest extends \PHPUnit\Framework\TestCase
{
    public function dummyTest(): void
    {
        // Assert::anything is OK
        Assert::assertEquals(5, 5);
        Assert::assertCount(1, [1, 2]);
        Assert::assertTrue(false);
        \PHPUnit\Framework\Assert::assertTrue(true);
    }
}
```
