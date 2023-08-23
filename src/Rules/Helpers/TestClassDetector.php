<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules\Helpers;

use Nette\Utils\Strings;

final class TestClassDetector
{
    /**
     * @var string
     */
    private const TEST_CLASS_ENDING_STRING = 'Test';

    public static function isUnitTest(string $namespace, string $className, string $unitTestNamespacePattern): bool
    {
        return Strings::contains($namespace, $unitTestNamespacePattern)
            && static::isTestClass($className);
    }

    public static function isTestClass(string $className): bool
    {
        return Strings::endsWith($className, self::TEST_CLASS_ENDING_STRING);
    }
}
