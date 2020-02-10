<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<StaticAssertOverThisAndStaticRule>
 */
final class StaticAssertOverThisAndStaticRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/data/static-assert-over-this-and-static-rule.php'], [
            [
                'Calling $this->assertSame is forbidden. Use PHPUnit\Framework\Assert::assertSame instead.',
                14,
            ],
            [
                'Calling static::assertSame is forbidden. Use PHPUnit\Framework\Assert::assertSame instead.',
                15,
            ],
            [
                'Calling $this->assertTrue is forbidden. Use PHPUnit\Framework\Assert::assertTrue instead.',
                16,
            ],
            [
                'Calling static::assertCount is forbidden. Use PHPUnit\Framework\Assert::assertCount instead.',
                17,
            ],
            [
                'Calling ExampleTestCase\StaticAssertOverThisAndStaticRule::assertTrue is forbidden. Use PHPUnit\Framework\Assert::assertTrue instead.',
                18,
            ],
            [
                'Calling ExampleTestCase\DummyTestCase::assertTrue is forbidden. Use PHPUnit\Framework\Assert::assertTrue instead.',
                19,
            ],
            [
                'Calling self::assertArrayHasKey is forbidden. Use PHPUnit\Framework\Assert::assertArrayHasKey instead.',
                20,
            ],
        ]);

        $this->analyse([__DIR__ . '/data/static-assert-over-this-and-static-rule-not-a-test.php'], []);
    }

    protected function getRule(): \PHPStan\Rules\Rule
    {
        return new StaticAssertOverThisAndStaticRule();
    }
}
