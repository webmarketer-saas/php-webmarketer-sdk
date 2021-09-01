<?php

namespace Webmarketer\Api\Project\EventTypes;

class EventTypeProjectField extends AbstractEventTypeField
{
    /**
     * Technical id of the field from project (user-field, statistic-field, state-field...)
     * [Required]
     *
     * @var string
     */
    public $fieldId;

    /**
     * Override the project field key with custom key for the event type
     * [Optional]
     *
     * @var string
     */
    public $key;
}
