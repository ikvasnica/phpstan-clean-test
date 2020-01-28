<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use Nette\Utils\Strings;

final class UnitTestRuleHelper
{
    /** @var string */
    private const TEST_CLASS_ENDING_STRING = 'Test';

    public static function isUnitTest(string $namespace, string $className, string $unitTestNamespacePattern): bool
    {
        return Strings::contains($namespace, $unitTestNamespacePattern)
            && Strings::endsWith($className, self::TEST_CLASS_ENDING_STRING);
    }
}
