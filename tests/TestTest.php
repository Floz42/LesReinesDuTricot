<?php 

namespace tests;

use PHPUnit\Framework\TestCase;

class TestTest extends TestCase
{
    public function testIfNumbersAreEquals()
    {
        $a = 5;
        $b = 5;

        $this->assertEquals($a,$b);
    }

}