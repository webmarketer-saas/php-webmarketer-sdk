<?php

namespace Webmarketer\Api\Project\EventTypes;

use DateTime;
use Webmarketer\Api\AbstractApiObject;

class EventType extends AbstractApiObject
{
    /**
     * Technical id
     *
     * @var string
     */
    public $_id;

    /**
     * Event name
     * [Required]
     *
     * @var string
     */
    public $name;

    /**
     * Event reference
     * [Required]
     *
     * @var string
     */
    public $reference;

    /**
     * Array of key => value eventType's metadata
     *
     * @var array
     */
    public $metadata;

    /**
     * Event project id
     *
     * @var string
     */
    public $projectId;

    /**
     * Phone parsing configuration
     *
     * @var array
     */
    public $phoneParsingConfig;

    /**
     * Event expiration policy configuration
     *
     * @var ExpirationPolicyFromProject | ExpirationPolicyFromEventType
     */
    public $expirationPolicy;

    /**
     * Event configured fields
     * [Required]
     *
     * @var EventTypeClassicField[] | EventTypeProjectField[]
     */
    public $fields;

    /**
     * Event creation date
     *
     * @var DateTime
     */
    public $createdAt;

    /**
     * Event last update date
     *
     * @var DateTime
     */
    public $updatedAt;
}
