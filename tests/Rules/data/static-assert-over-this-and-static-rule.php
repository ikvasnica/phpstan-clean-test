<?php

declare(strict_types=1);

namespace ExampleTestCase;

use PHPUnit\Framework\Assert;

final class StaticAssertOverThisAndStaticRule extends DummyTestCase
{
	public function dummyTest(): void
	{
		// should fail
		$this->assertSame(5, 5);
		static::assertSame(5, 5);
		$this->assertTrue(false);
		static::assertCount(0, []);
		\ExampleTestCase\StaticAssertOverThisAndStaticRule::assertTrue(true);
		DummyTestCase::assertTrue(true);
		self::assertArrayHasKey(5, [5]);

		// Assert::anything is OK
		Assert::assertEquals(5, 5);
		Assert::assertCount(1, [1, 2]);
		Assert::assertTrue(false);
		\PHPUnit\Framework\Assert::assertTrue(true);
	}
}
