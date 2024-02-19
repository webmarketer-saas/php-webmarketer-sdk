<?php

namespace Webmarketer\Api\Project\Events;

use DateTime;
use Webmarketer\Api\AbstractApiObject;

class EventPayload extends AbstractApiObject
{
    /**
     * Event project ID
     * [Required]
     *
     * @var string
     */
    public $projectId;

    /**
     * Event nonce
     * [Required]
     *
     * @var string
     */
    public $nonce;

    /**
     * Event type slug
     * [Required]
     *
     * @var string;
     */
    public $eventType;

    /**
     * Event tracking ID
     * [Required]
     *
     * @var string;
     */
    public $trackerId;

    /**
     * Event payload
     * [Required]
     *
     * @var array;
     */
    public $data;

    /**
     * Event date (useful to antedate the event, optional)
     *
     * @var DateTime|null
     */
    public $eventDate;
}