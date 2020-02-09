<?php

declare(strict_types=1);

namespace ExampleTestCase;

use PHPUnit\Framework\Assert;

final class StaticAssertOverThisAndStaticRuleNotATest
{
	public function dummyTest(): void
	{
		// everything is OK here
		$this->assertSame(5, 5);
		static::assertCount(0, []);
		\ExampleTestCase\StaticAssertOverThisAndStaticRule::assertTrue(true);
		DummyTestCase::assertTrue(true);
		self::assertSame(5, 5);

		Assert::assertEquals(5, 5);
		Assert::assertCount(1, [1, 2]);
		Assert::assertTrue(false);
		\PHPUnit\Framework\Assert::assertTrue(true);
	}

	private function assertSame($first, $second): void
	{
		// nothing
	}

	private static function assertCount($first, $second): void
	{
		// nothing
	}
}
