<?php

declare(strict_types=1);

namespace ExampleTestCase;

use PHPUnit\Framework\TestCase;

abstract class DummyTestCase extends TestCase
{
	protected static function assertSomethingSpecial(): void
	{
		// intentionally does nothing
	}
}
