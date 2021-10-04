<?php

namespace Webmarketer\Api\Project\EventTypes;

class ExpirationPolicyFromEventType extends AbstractExpirationPolicy
{
    public $from = "event-type";

    /**
     * Determine the expiration policy in days
     * Default to null (no expiration)
     * [Required]
     *
     * @var int | null
     */
    public $retentionDurationInDays = null;
}
