<?php

namespace Webmarketer\Api\Project\EventTypes;

use Webmarketer\Api\AbstractApiObject;

class AbstractEventTypeField extends AbstractApiObject
{
    /**
     * Determine whether the field is optional in event type or not
     * [Required]
     *
     * @var boolean
     */
    public $optional;
}
