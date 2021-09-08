<?php

namespace Webmarketer\Api\Project\TrafficSources;

use DateTime;

class TrafficSourceUrlCheck
{
    /**
     * Last traffic source url configuration check
     *
     * @var DateTime
     */
    public $lastCheck;

    /**
     * Status of the url configuration
     * - valid
     * - invalid
     * - pending
     * - unavailable
     *
     * @var string
     */
    public $status;
}
