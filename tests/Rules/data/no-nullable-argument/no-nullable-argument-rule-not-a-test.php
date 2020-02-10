<?php

declare(strict_types=1);

namespace ExampleTestCase;

use PHPUnit\Framework\Assert;

final class NoNullableArgumentRule
{
	public function testMethodWithNullableArgument(?string $expectedValue): void
	{
		Assert::assertSame($expectedValue, 'something');
	}

	public function someNonTestMethod(?string $expectedValue): void
	{
		// anything can be passed into this method
	}

	public function testMethodWithMissingTypeArgument($mixedValue): void
	{
		Assert::assertSame($mixedValue, 'something');
	}

	private function testSomethingMethod(?string $something): void
	{
		// nothing
	}

	protected function testSomethingElseMethod(?int $maybeNumberMaybeNot): void
	{
		// nothing
	}
}
