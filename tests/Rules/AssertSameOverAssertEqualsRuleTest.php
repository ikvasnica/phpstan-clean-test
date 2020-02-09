<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<AssertSameOverAssertEqualsRule>
 */
final class AssertSameOverAssertEqualsRuleTest extends RuleTestCase
{
    private const ERROR_MESSAGE = 'Using "assertEquals" is forbidden with a scalar type as an argument. Use "assertSame" instead.';

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/data/assert-same-over-assert-equals-rule.php'], [
            [self::ERROR_MESSAGE, 24],
            [self::ERROR_MESSAGE, 25],
            [self::ERROR_MESSAGE, 26],
            [self::ERROR_MESSAGE, 27],
            [self::ERROR_MESSAGE, 28],
            [self::ERROR_MESSAGE, 29],
            [self::ERROR_MESSAGE, 30],
            [self::ERROR_MESSAGE, 31],
            [self::ERROR_MESSAGE, 32],
            [self::ERROR_MESSAGE, 33],
            [self::ERROR_MESSAGE, 34],
            [self::ERROR_MESSAGE, 35],
            [self::ERROR_MESSAGE, 36],
        ]);

        $this->analyse([__DIR__ . '/data/assert-same-over-assert-equals-rule-not-a-test.php'], []);
    }

    protected function getRule(): \PHPStan\Rules\Rule
    {
        return new AssertSameOverAssertEqualsRule();
    }
}
