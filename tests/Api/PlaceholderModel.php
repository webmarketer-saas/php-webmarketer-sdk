<?php

namespace Webmarketer\Tests\Api;

use Webmarketer\Api\AbstractApiObject;

class PlaceholderModel extends AbstractApiObject
{
    /**
     * @var string
     */
    public $_id;

    /**
     * @var string[]
     */
    public $test_values;
}
