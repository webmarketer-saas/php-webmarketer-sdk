<?php

namespace Webmarketer\Api\Project\Events;

class EventAddOperation extends AbstractEventOperation
{
    /**
     * Value to apply
     * [Required]
     *
     * @var mixed
     */
    public $value;

    private $op = 'add';
}
