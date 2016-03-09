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
use LitGroup\JsonReader\JsonReader;

class JsonReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonReader
     */
    private $reader;


    protected function setUp()
    {
        $this->reader = new JsonReader();
    }

    protected function tearDown()
    {
        $this->reader = null;
    }

    public function getInvalidJson()
    {
        return [
            [null],
            [''],
            ['null'],
            ['true'],
            ['false'],
            ['10'],
            ['"String"'],
            ['malformed json'],
        ];
    }

    public function getCorrectJsonArrays()
    {
        return [
            ['[]'],
            ['["element1", "element2"]']
        ];
    }

    public function getCorrectJsonObjects()
    {
        return [
            ['{}'],
            ['{"first_name": "Roman", "last_name": "Shamritskiy"}']
        ];
    }

    /**
     * @param string $json
     *
     * @dataProvider getCorrectJsonArrays
     */
    public function testReadArray($json)
    {
        $this->assertInstanceOf(JsonArray::class, $this->reader->readArray($json));
    }

    public function getReadArrayFormatExceptionTests()
    {
        return array_merge(
            $this->getInvalidJson(),
            [['{}']]
        );
    }

    /**
     * @param string $json
     * @dataProvider getReadArrayFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testReadArrayFormatException($json)
    {
        $this->reader->readArray($json);
    }

    /**
     * @param string $json
     *
     * @dataProvider getCorrectJsonObjects
     */
    public function testReadObject($json)
    {
        $this->assertInstanceOf(JsonObject::class, $this->reader->readObject($json));
    }

    public function getReadObjectFormatExceptionTests()
    {
        return array_merge(
            $this->getInvalidJson(),
            [['[]']]
        );
    }

    /**
     * @param string $json
     * @dataProvider getReadObjectFormatExceptionTests
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testReadObjectFormatException($json)
    {
        $this->reader->readObject($json);
    }

    public function getReadTests()
    {
        return [
            ['{}', JsonObject::class],
            ['[]', JsonArray::class],
        ];
    }

    /**
     * @param string $json
     * @param string $class
     * @dataProvider getReadTests
     */
    public function testRead($json, $class)
    {
        $this->assertInstanceOf($class, $this->reader->read($json));
    }

    /**
     * @param string $json
     * @dataProvider getInvalidJson
     * @expectedException \LitGroup\JsonReader\Exception\FormatException
     */
    public function testReadFormatException($json)
    {
        $this->reader->read($json);
    }
}
