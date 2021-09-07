<?php

namespace Webmarketer\Api\Project\Events;

use Webmarketer\Api\AbstractApiObject;

class Event extends AbstractApiObject
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
     * @var string;
     */
    public $data;
}