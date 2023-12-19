<?php

namespace Webmarketer\Api\Project\EventTypes;

use Webmarketer\Api\AbstractApiObject;

class UpdateEventTypePayload extends AbstractApiObject
{
    /**
     * EventType name
     *
     * @var string
     */
    public $name;

    /**
     * EventType fields
     * /!\ WARNING : all fields should be provided as well as new fields (as Webmarketer create a new version of the event type)
     *
     * @var EventTypeField[]
     */
    public $fields;

    /**
     * EventType expiration policy config
     *
     * @var ExpirationPolicyFromProject | ExpirationPolicyFromEventType
     */
    public $expirationPolicy;

    /**
     * Determine if the event type is attribuable or not
     *
     * @var boolean
     */
    public $attributable;
}