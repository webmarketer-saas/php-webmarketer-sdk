<?php

namespace Webmarketer\Api\Project\CustomColumns;

use Webmarketer\Api\AbstractApiObject;

class CustomColumn extends AbstractApiObject
{
    /**
     * Technical ID
     *
     * @var string
     */
    public $id;

    /**
     * Project ID of the custom column
     *
     * @var string
     */
    public $projectId;

    /**
     * Custom column key
     *
     * @var string
     */
    public $key;

    /**
     * Custom column name
     *
     * @var string
     */
    public $name;

    /**
     * Custom column format
     * - currency
     * - number
     * - percent
     * - time
     *
     * @var string
     */
    public $format;

    /**
     * Array of configured formulas for the custom column
     *
     * @var array
     */
    public $symbols;
}
