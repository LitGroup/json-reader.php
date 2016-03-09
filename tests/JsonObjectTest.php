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

class JsonObjectTest extends TestCase
{
    public function testHas()
    {
        $object = $this->readObject('{"username": "john", "email": null}');

        $this->assertTrue($object->has('username'));
        $this->assertTrue($object->has('email'));
        $this->assertFalse($object->has('phone'));
    }

    public function testIsNull()
    {
        $object = $this->readObject('{"username": "john", "email": null}');

        $this->assertTrue($object->isNull('email'));
        $this->assertFalse($object->isNull('username'));
    }

    /**
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testIsNullFormatException()
    {
        $this->readObject('{}')->isNull('not_exists');
    }

    public function testIsNotNull()
    {
        $object = $this->readObject('{"username": "john", "email": null}');

        $this->assertTrue($object->isNotNull('username'));
        $this->assertFalse($object->isNotNull('email'));
    }

    /**
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testIsNotNullFormatException()
    {
        $this->readObject('{}')->isNotNull('not_exists');
    }

    public function testInt()
    {
        $object = $this->readObject(
            '{"amount": 10000}'
        );

        $this->assertSame(10000, $object->getInt('amount'));
    }

    public function getIntFormatExceptionTests()
    {
        return [
            ['{}'],
            ['{"amount": null}'],
            ['{"amount": "1234"}'],
        ];
    }

    /**
     * @dataProvider getIntFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testIntFormatException($json)
    {
        $this->readObject($json)->getInt('amount');
    }

    public function testDouble()
    {
        $object = $this->readObject('{"rate": 10.4, "amount": 10000}');

        $this->assertInternalType('double', $object->getDouble('rate'));
        $this->assertEquals(10.4, $object->getDouble('rate'));

        $this->assertInternalType('double', $object->getDouble('amount'));
        $this->assertEquals(10000.0, $object->GetDouble('amount'));
    }
    public function getDoubleFormatExceptionTests()
    {
        return [
            ['{}'],
            ['{"rate": null}'],
            ['{"rate": "1234"}'],
        ];
    }

    /**
     * @dataProvider getDoubleFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testDoubleFormatException($json)
    {
        $this->readObject($json)->getDouble('rate');
    }

    public function testBool()
    {
        $object = $this->readObject('{"is_active": true, "is_admin": false}');

        $this->assertTrue($object->getBool('is_active'));
        $this->assertFalse($object->getBool('is_admin'));
    }

    public function getBoolFormatExceptionTests()
    {
        return [
            ['{}'],
            ['{"is_active": null}'],
            ['{"is_active": "true"}'],
            ['{"is_active": ""}'],
        ];
    }

    /**
     * @dataProvider getBoolFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testBoolFormatException($json)
    {
        $this->readObject($json)->getBool('is_active');
    }

    public function testString()
    {
        $object = $this->readObject('{"username": "john", "email": "", "rating": "100"}');

        $this->assertSame('john', $object->getString('username'));
        $this->assertSame('', $object->getString('email'));
        $this->assertSame('100', $object->getString('rating'));
    }

    public function getStringFormatExceptionTests()
    {
        return [
            ['{}'],
            ['{"username": null}'],
            ['{"username": 1000000}']
        ];
    }

    /**
     * @dataProvider getStringFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testStringFormatException($json)
    {
        $this->readObject($json)->getString('username');
    }

    public function testJsonObject()
    {
        $object = $this->readObject('{"user": {"username": "john"}}');

        $this->assertInstanceOf(JsonObject::class, $object->getJsonObject('user'));
        $this->assertSame('john', $object->getJsonObject('user')->getString('username'));
    }

    public function getJsonObjectFormatExceptionTests()
    {
        return [
            ['{}'],
            ['{"user": null}'],
            ['{"user": "just a name"}'],
        ];
    }

    /**
     * @dataProvider getJsonObjectFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testJsonObjectFormatException($json)
    {
        $this->readObject($json)->getJsonObject('user');
    }

    public function testJsonArray()
    {
        $array = $this->readObject('{"hello": ["world"]}');

        $this->assertInstanceOf(JsonArray::class, $array->getJsonArray("hello"));
        $this->assertSame('world', $array->getJsonArray("hello")->getString(0));
    }


    public function getJsonArrayFormatExceptionTests()
    {
        return [
            ['{}'],
            ['{"list": null}'],
            ['{"list": {}}'],
            ['{"list": ""}'],
        ];
    }

    /**
     * @dataProvider getJsonArrayFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testJsonArrayFormatException($json)
    {
        $this->readObject($json)->getJsonArray('list');
    }

    /**
     * @param string $json
     * @return JsonObject
     */
    private function readObject($json)
    {
        return self::$reader->readObject($json);
    }
}

