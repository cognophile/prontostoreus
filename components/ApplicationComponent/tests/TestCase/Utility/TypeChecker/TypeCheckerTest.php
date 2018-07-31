<?php

namespace ApplicationComponent\Test\TestCase\Utility\TypeChecker;

use Cake\TestSuite\TestCase;
use ApplicationComponent\Utility\TypeChecker\TypeChecker;

class TypeCheckerTest extends TestCase
{
    public $fixtures = [

    ];

    public function validNumericValuesProvider()
    {
        return [
            'Numeric Zero' => ["0"],
            'Numeric One' => ["1"],
            'Numeric Multiple' => ["1234"],
        ];
    }

    public function invalidNonNumericValuesProvider()
    {
        return [
            'Character Upper Case' => ["A"],
            'Character Lower Case' => ["a"],
            'Character and Numeric' => ["A1"],
            'Character Upper Case' => ["@"],
            'Numeric Decimal' => ["1.25"],
        ];
    }

    /**
     * @dataProvider validNumericValuesProvider
     */
    public function testIsNumericWithNumericValuesReturnsTrue($value)
    {
        $actual = TypeChecker::isNumeric($value);
        $this->assertTrue($actual);
    }

    /**
     * @dataProvider invalidNonNumericValuesProvider
     */
    public function testIsNumericWithNonNumericValuesReturnsFalse($value)
    {
        $actual = TypeChecker::isNumeric($value);
        $this->assertFalse($actual);
    }
}