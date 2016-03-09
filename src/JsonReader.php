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

use LitGroup\Json\Decoder;
use LitGroup\Json\DecoderConfiguration;
use LitGroup\Json\Exception\JsonException;
use LitGroup\JsonReader\Exception\FormatException;

/**
 * Class JsonReader.
 *
 * @author Roman Shamritskiy <roman@litgroup.ru>
 */
class JsonReader
{
    /**
     * @var Decoder
     */
    private $decoder;


    /**
     * JsonReader constructor.
     */
    public function __construct()
    {
        $this->decoder = new Decoder(
            (new DecoderConfiguration())
                ->setUseAssoc(false)
        );
    }

    /**
     * @param string $json
     *
     * @return JsonStructure
     *
     * @throws FormatException
     */
    public function read($json)
    {
        $input = $this->decodeJson($json);

        if ($this->isObject($input)) {
            return new JsonObject($input);
        } elseif ($this->isArray($input)) {
            return new JsonArray($input);
        } else {
            throw new FormatException('Incoming JSON does not represents an object on an array.');
        }
    }

    /**
     * @param string $json
     *
     * @return JsonObject
     *
     * @throws FormatException
     */
    public function readObject($json)
    {
        return new JsonObject(
            $this->filterObject(
                $this->decodeJson($json)
            )
        );
    }

    /**
     * @param string $json
     *
     * @return JsonArray
     *
     * @throws FormatException
     */
    public function readArray($json)
    {
        return new JsonArray(
            $this->filterArray(
                $this->decodeJson($json)
            )
        );
    }

    /**
     * @param string $json
     *
     * @return mixed
     *
     * @throws FormatException
     */
    private function decodeJson($json)
    {
        try {
            return $this->decoder->decode($json);
        } catch (JsonException $e) {
            throw new FormatException($e->getMessage());
        }
    }

    /**
     * @param mixed $input
     *
     * @return \stdClass
     *
     * @throws FormatException
     */
    private function filterObject($input)
    {
        if ($this->isObject($input)) {
            return $input;
        }

        throw new FormatException('Incoming JSON does not represents an object.');
    }

    /**
     * @param mixed $input
     *
     * @return array
     *
     * @throws FormatException
     */
    private function filterArray($input)
    {
        if ($this->isArray($input)) {
            return $input;
        }

        throw new FormatException('Incoming JSON does not represents an array.');
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    private function isObject($input)
    {
        return ($input instanceof \stdClass);
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    private function isArray($input)
    {
        return is_array($input);
    }
}