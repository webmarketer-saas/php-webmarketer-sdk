<?php

namespace Webmarketer\Api;

use stdClass;

abstract class AbstractApiObject
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

    /**
     * @return array
     */
    public function getObject()
    {
        return get_object_vars($this);
    }
}
