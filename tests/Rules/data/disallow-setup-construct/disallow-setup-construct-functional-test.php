<?php

declare(strict_types=1);

namespace ExampleTestCase\Functional;

class DisallowSetupConstructFunctionalTest extends \PHPUnit\Framework\TestCase
{
    private $something;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->something = true;
    }

    public function testSomeThing(): void
    {
        $this->assertTrue($this->something);
    }
}
