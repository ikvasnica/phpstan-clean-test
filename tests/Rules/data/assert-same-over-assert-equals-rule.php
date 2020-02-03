<?php

declare(strict_types=1);

namespace ExampleTestCase;

use Exception;
use PHPUnit\Framework\TestCase;

final class AssertSameOverAssertEqualsRule extends TestCase
{
	public function dummyTest(): void
	{
		$string = 'someString';
		$integer = 10;
		$boolean = false;
		$null = null;

		// assertSame is OK
		$this->assertSame(5, 5);
		static::assertSame(5, 5);

		// should fail
		$this->assertEquals('', null);
		$this->assertEquals(null, '');
		static::assertEquals(null, '');
		$this->assertEquals(2, 3);
		$this->assertEquals(2.2, 2.5);
		static::assertEquals((int) '2', (int) '3');
		$this->assertEquals(true, false);
		$this->assertEquals($string, $string);
		$this->assertEquals($integer, $integer);
		$this->assertEquals($boolean, $boolean);
		$this->assertEquals($null, $null);
		$this->assertEquals((string) new Exception(), (string) new Exception());

		// these uses of assertEquals are OK
		$this->assertEquals([], []);
		$this->assertEquals(new Exception(), new Exception());
		static::assertEquals(new Exception(), new Exception());
	}
}
