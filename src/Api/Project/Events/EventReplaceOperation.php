<?php

namespace Webmarketer\Api\Project\Events;

class EventReplaceOperation extends AbstractEventOperation
{
    /**
     * Modified value
     * [Required]
     *
     * @var mixed
     */
    public $value;

    private $op = 'replace';
}
