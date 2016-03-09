<?php
/**
 * This file is part of the "litgroup/json-reader" package.
 *
 * (c) Roman Shamritskiy <roman@litgroup.ru>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests\LitGroup\JsonReader;

use LitGroup\JsonReader\JsonArray;
use LitGroup\JsonReader\JsonObject;

class JsonArrayTest extends TestCase
{
    public function testHas()
    {
        $array = $this->readArray('["john", null]');

        $this->assertTrue($array->has(0));
        $this->assertTrue($array->has(1));
        $this->assertFalse($array->has(2));
    }

    public function getCountTests()
    {
        return [
            [0, '[]'],
            [1, '["some"]'],
            [2, '["some", null]'],
        ];
    }

    /**
     * @dataProvider getCountTests
     */
    public function testCount($count, $json)
    {
        $this->assertCount($count, $this->readArray($json));
    }

    public function testIsNull()
    {
        $array = $this->readArray('["john", null]');

        $this->assertTrue($array->isNull(1));
        $this->assertFalse($array->isNull(0));
    }

    /**
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testIsNullFormatException()
    {
        $this->readArray('[]')->isNull(0);
    }

    public function testIsNotNull()
    {
        $array = $this->readArray('["john", null]');

        $this->assertTrue($array->isNotNull(0));
        $this->assertFalse($array->isNotNull(1));
    }

    /**
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testIsNotNullFormatException()
    {
        $this->readArray('[]')->isNotNull(0);
    }

    public function testInt()
    {
        $array = $this->readArray('[10, 20]');

        $this->assertSame(10, $array->getInt(0));
        $this->assertSame(20, $array->getInt(1));
    }

    public function getIntFormatExceptionTests()
    {
        return [
            ['[]'],
            ['[null]'],
            ['[10.4]'],
            ['["10"]'],
        ];
    }

    /**
     * @dataProvider getIntFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testIntFormatException($json)
    {
        $this->readArray($json)->getInt(0);
    }

    public function testDouble()
    {
        $array = $this->readArray('[10, 10.4]');

        $this->assertInternalType('double', $array->getDouble(0));
        $this->assertEquals(10.0, $array->getDouble(0));

        $this->assertInternalType('double', $array->getDouble(1));
        $this->assertEquals(10.4, $array->GetDouble(1));
    }
    public function getDoubleFormatExceptionTests()
    {
        return [
            ['[]'],
            ['[null]'],
            ['["10.4"]'],
        ];
    }

    /**
     * @dataProvider getDoubleFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testDoubleFormatException($json)
    {
        $this->readArray($json)->getDouble(0);
    }

    public function testBool()
    {
        $array = $this->readArray('[true, false]');

        $this->assertTrue($array->getBool(0));
        $this->assertFalse($array->getBool(1));
    }

    public function getBoolFormatExceptionTests()
    {
        return [
            ['[]'],
            ['[null]'],
            ['["true"]'],
            ['[""]'],
        ];
    }

    /**
     * @dataProvider getBoolFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testBoolFormatException($json)
    {
        $this->readArray($json)->getBool(0);
    }

    public function testString()
    {
        $array = $this->readArray('["john", "", "100"]');

        $this->assertSame('john', $array->getString(0));
        $this->assertSame('', $array->getString(1));
        $this->assertSame('100', $array->getString(2));
    }

    public function getStringFormatExceptionTests()
    {
        return [
            ['[]'],
            ['[null]'],
            ['[100]']
        ];
    }

    /**
     * @dataProvider getStringFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testStringFormatException($json)
    {
        $this->readArray($json)->getString(0);
    }

    public function testJsonObject()
    {
        $array = $this->readArray('[{"username": "john"}]');

        $this->assertInstanceOf(JsonObject::class, $array->getJsonObject(0));
        $this->assertSame('john', $array->getJsonObject('user')->getString('username'));
    }

    public function getJsonObjectFormatExceptionTests()
    {
        return [
            ['[]'],
            ['[null]'],
            ['["I am a user"]'],
        ];
    }

    /**
     * @dataProvider getJsonObjectFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testJsonObjectFormatException($json)
    {
        $this->readArray($json)->getJsonObject(0);
    }

    public function testJsonArray()
    {
        $array = $this->readArray('[["hello"]]');

        $this->assertInstanceOf(JsonArray::class, $array->getJsonArray(0));
        $this->assertSame('hello', $array->getJsonArray(0)->getString(0));
    }


    public function getJsonArrayFormatExceptionTests()
    {
        return [
            ['[]'],
            ['[null]'],
            ['[{}]'],
            ['["hello"]'],
        ];
    }

    /**
     * @dataProvider getJsonArrayFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testJsonArrayFormatException($json)
    {
        $this->readArray($json)->getJsonArray(0);
    }

    /**
     * @param string $json
     * @return JsonArray
     */
    private function readArray($json)
    {
        return self::$reader->readArray($json);
    }
}
