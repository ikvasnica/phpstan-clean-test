<?php

declare(strict_types=1);

namespace ExampleTestCase;

final class AssertSameOverAssertEqualsRuleNotATest
{
	public function dummyTest(): void
	{
		$this->assertEquals('', null);
		$this->assertEquals(null, '');
	}

	private function assertEquals($firstArgument, $secondArgument): void
	{
		// empty
	}
}
