<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<UnitExtendsFromTestCaseRule>
 */
final class UnitExtendsFromTestCaseRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/data/unit-extends/unit-extends-exception.php'], []);
        $this->analyse([__DIR__ . '/data/unit-extends/unit-extends-no-namespace.php'], []);
        $this->analyse([__DIR__ . '/data/unit-extends/unit-extends-functional-test.php'], []);
        $this->analyse([__DIR__ . '/data/unit-extends/unit-extends-ok-test.php'], []);
        $this->analyse([__DIR__ . '/data/unit-extends/unit-extends-not-extending-test.php'], []);

        $this->analyse([__DIR__ . '/data/unit-extends/unit-extends-invalid-test.php'], [
            [
                'Extending from the class "Dummy\FunctionalDummyTest" is not allowed in unit tests.',
                07,
            ],
        ]);
    }

    protected function getRule(): Rule
    {
        return new UnitExtendsFromTestCaseRule([\PHPUnit\Framework\TestCase::class], 'Unit');
    }
}
