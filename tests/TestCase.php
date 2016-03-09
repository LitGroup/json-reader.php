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

use LitGroup\JsonReader\JsonReader;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonReader
     */
    protected static $reader;

    public static function setUpBeforeClass()
    {
        self::$reader = new JsonReader();
    }

    public static function tearDownAfterClass()
    {
        self::$reader = null;
    }
}