<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<NoNullableArgumentRule>
 */
final class NoNullableArgumentRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/data/no-nullable-argument/no-nullable-argument-rule.php'], [
            [
                'Argument "expectedValue" of the method "testMethodWithNullableArgument" is nullable. Nullable arguments in test methods are forbidden.',
                11,
            ],
            [
                'Argument "mixedValue" of the method "testMethodWithSeveralInvalidArguments" has no type. Arguments with no type and nullable arguments in test methods are forbidden.',
                22,
            ],
            [
                'Argument "maybeException" of the method "testMethodWithSeveralInvalidArguments" is nullable. Nullable arguments in test methods are forbidden.',
                22,
            ],
            [
                'Argument "expectedValue" of the method "someTestMethod" is nullable. Nullable arguments in test methods are forbidden.',
                37,
            ],
        ]);

        $this->analyse([__DIR__ . '/data/no-nullable-argument/no-nullable-argument-rule-not-a-test.php'], []);
    }

    protected function getRule(): \PHPStan\Rules\Rule
    {
        return new NoNullableArgumentRule();
    }
}
