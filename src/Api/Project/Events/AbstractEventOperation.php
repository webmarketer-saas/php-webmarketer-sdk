<?php

namespace Webmarketer\Api\Project\Events;

abstract class AbstractEventOperation
{
    /**
     * Path of the key concerned by the operation
     * [Required]
     *
     * @var string
     */
    public $path;
}