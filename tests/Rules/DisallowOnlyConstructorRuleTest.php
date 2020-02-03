<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<DisallowSetupAndConstructorRule>
 */
final class DisallowOnlyConstructorRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/data/disallow-setup-construct/disallow-setup-construct-ok-test.php'], []);
        $this->analyse([__DIR__ . '/data/disallow-setup-construct/disallow-setup-construct-functional-test.php'], []);
        $this->analyse([__DIR__ . '/data/disallow-setup-construct/disallow-setup-construct-invalid-test.php'], [
            [
                'Declaring the method "__construct" in unit tests is forbidden.',
                11,
            ],
        ]);
    }

    protected function getRule(): \PHPStan\Rules\Rule
    {
        return new DisallowSetupAndConstructorRule('Unit', true);
    }
}
