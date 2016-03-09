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

/**
 * Represents JSON-object.
 *
 * @author Roman Shamritskiy
 */
class JsonObject extends JsonStructure
{
    /**
     * @var \stdClass
     */
    private $object;


    /**
     * JsonObject constructor.
     *
     * @param \stdClass $obj
     */
    public function __construct(\stdClass $obj)
    {
        $this->object = $obj;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return property_exists($this->object, (string) $name);
    }

    /**
     * @param string $name
     *
     * @return int
     *
     * @throws FormatException
     */
    public function getInt($name)
    {
        $value = $this->get($name);
        if (is_int($value)) {
            return $value;
        }

        throw new FormatException();
    }

    /**
     * @param string $name
     *
     * @return double
     *
     * @throws FormatException
     */
    public function getDouble($name)
    {
        $value = $this->get($name);
        if (is_double($value) || is_int($value)) {
            return (double) $value;
        }

        throw new FormatException();
    }

    /**
     * @param string $name
     *
     * @return bool
     *
     * @throws FormatException
     */
    public function getBool($name)
    {
        $value = $this->get($name);
        if (is_bool($value)) {
            return $value;
        }

        throw new FormatException();
    }

    /**
     * @param string $name
     *
     * @return bool
     *
     * @throws FormatException
     */
    public function getString($name)
    {
        $value = $this->get($name);
        if (is_string($value)) {
            return $value;
        }

        throw new FormatException();
    }

    /**
     * @param string $name
     *
     * @return JsonArray
     *
     * @throws FormatException
     */
    public function getJsonArray($name)
    {
        $value = $this->get($name);
        if (is_array($value)) {
            return new JsonArray($value);
        }

        throw new FormatException();
    }

    /**
     * @param string $name
     *
     * @return JsonObject
     *
     * @throws FormatException
     */
    public function getJsonObject($name)
    {
        $value = $this->get($name);
        if (is_object($value)) {
            return new JsonObject($value);
        }

        throw new FormatException();
    }

    /**
     * @param string $name
     *
     * @return bool
     *
     * @throws FormatException
     */
    public function isNull($name)
    {
        return is_null($this->get($name));
    }

    /**
     * @param string $name
     *
     * @return bool
     *
     * @throws FormatException
     */
    public function isNotNull($name)
    {
        return !$this->isNull($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws FormatException
     */
    private function get($name)
    {
        if (!$this->has($name)) {
            throw new FormatException();
        }

        return $this->object->{$name};
    }
}