<?php

namespace Webmarketer\Api;

use stdClass;

abstract class AbstractApiObject implements \JsonSerializable
{
    /**
     * @param stdClass | array $payload
     */
    public static function createFromArray($payload)
    {
        $instance = new static();
        foreach ((object) $payload as $key => $value) {
            if (property_exists(static::class, $key)) {
                $instance->$key = $value;
            }
        }
        return $instance;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($item) {
            return !is_null($item);
        });
    }
}
