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
     * Event project id
     *
     * @var string
     */
    public $projectId;

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
