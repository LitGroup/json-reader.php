<?php
/**
 * This file is part of the "litgroup/json-reader" package.
 *
 * (c) Roman Shamritskiy <roman@litgroup.ru>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace LitGroup\JsonReader;

use LitGroup\JsonReader\Exception\FormatException;

class JsonArray extends JsonStructure implements \Countable
{
    /**
     * @var array
     */
    private $array;


    /**
     * JsonArray constructor.
     *
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * @param int $index
     *
     * @return bool
     */
    public function hasIndex($index)
    {
        return intval($index) < $this->count();
    }

    /**
     * @param int $index
     *
     * @return int
     *
     * @throws FormatException
     */
    public function getInt($index)
    {
        $value = $this->get($index);
        if (is_int($value)) {
            return $value;
        }

        throw new FormatException();
    }

    /**
     * @param int $index
     *
     * @return double
     *
     * @throws FormatException
     */
    public function getDouble($index)
    {
        $value = $this->get($index);
        if (is_double($value) || is_int($value)) {
            return (double) $value;
        }

        throw new FormatException();
    }

    /**
     * @param int $index
     *
     * @return bool
     *
     * @throws FormatException
     */
    public function getBool($index)
    {
        $value = $this->get($index);
        if (is_bool($value)) {
            return $value;
        }

        throw new FormatException();
    }

    /**
     * @param int $index
     *
     * @return bool
     *
     * @throws FormatException
     */
    public function getString($index)
    {
        $value = $this->get($index);
        if (is_string($value)) {
            return $value;
        }

        throw new FormatException();
    }

    /**
     * @param int $index
     *
     * @return JsonArray
     *
     * @throws FormatException
     */
    public function getJsonArray($index)
    {
        $value = $this->get($index);
        if (is_array($value)) {
            return new JsonArray($value);
        }

        throw new FormatException();
    }

    /**
     * @param int $index
     *
     * @return JsonObject
     *
     * @throws FormatException
     */
    public function getJsonObject($index)
    {
        $value = $this->get($index);
        if (is_object($value)) {
            return new JsonObject($value);
        }

        throw new FormatException();
    }

    /**
     * @param int $index
     *
     * @return bool
     *
     * @throws FormatException
     */
    public function isNull($index)
    {
        return is_null($this->get($index));
    }

    /**
     * @param int $index
     *
     * @return bool
     *
     * @throws FormatException
     */
    public function isNotNull($index)
    {
        return !$this->isNull($index);
    }

    /**
     * Returns amount of elements in this array.
     *
     * @return int
     */
    public function count()
    {
        return count($this->array);
    }

    /**
     * @param int $index
     *
     * @return mixed
     *
     * @throws FormatException
     */
    private function get($index)
    {
        if ($this->hasIndex($index)) {
            return $this->array[intval($index)];
        }

        throw new FormatException();
    }
}