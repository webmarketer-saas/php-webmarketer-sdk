<?php

namespace Webmarketer\Api\Project\Events;

use DateTime;
use Webmarketer\Api\AbstractApiObject;

class Event extends AbstractApiObject
{
    /**
     * Event project ID
     *
     * @var string
     */
    public $projectId;

    /**
     * Event user ID
     *
     * @var string
     */
    public $userId;

    /**
     * Associative array of event eventFields
     *
     * @var array
     */
    public $event;

    /**
     * Associative array of event userFields
     *
     * @var array
     */
    public $user;

    /**
     * Event metrics
     *
     * @var array
     */
    public $metrics;

    /**
     * Event type slug
     *
     * @var string;
     */
    public $eventType;

    /**
     * Expiration configuration of the event
     *
     * @var array | null
     */
    public $expirationPolicy;

    /**
     * Event creation date
     *
     * @var DateTime
     */
    public $createdAt;

    /**
     * Event update date
     *
     * @var DateTime
     */
    public $updatedAt;
}