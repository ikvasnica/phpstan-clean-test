<?php

declare(strict_types=1);

namespace ExampleTestCase;

use PHPUnit\Framework\Assert;

final class NoNullableArgumentRuleTest extends DummyTestCase
{
	public function testMethodWithNullableArgument(int $expectedNumber, ?string $expectedValue): void
	{
		Assert::assertSame($expectedValue, 'something');
		Assert::assertSame($expectedNumber, 10);
	}

	public function testMethodWithNoArgument(): void
	{
		Assert::assertNull(null);
	}

	public function testMethodWithSeveralInvalidArguments($mixedValue, ?\Exception $maybeException): void
	{
		Assert::assertSame($mixedValue, 5);
		Assert::assertNull($maybeException);
	}

	public function someNonTestMethod(?string $expectedValue): void
	{
		// anything can be passed into this method
	}

	/**
	 * @test
	 * @param string|null $expectedValue
	 */
	public function someTestMethod(?string $expectedValue): void
	{
		// this one is test method due to the annotation
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
