<?php

namespace Webmarketer\Api\Project\TrafficSources;

use DateTime;
use Webmarketer\Api\AbstractApiObject;

class TrafficSource extends AbstractApiObject
{
    /**
     * Technical id
     *
     * @var string
     */
    public $_id;

    /**
     * Project ID where is set up source
     *
     * @var string
     */
    public $projectId;

    /**
     * Credential ID associated
     *
     * @var string
     */
    public $credentialId;

    /**
     * Trafic source name
     *
     * @var string
     */
    public $name;

    /**
     * Trafic source type
     *
     * @var string
     */
    public $type;

    /**
     * Webmarketer ID belonging to this source
     *
     * @var string
     */
    public $wmktId;

    /**
     * Determine the source where data are imported
     *
     * @var string
     */
    public $dataSource;

    /**
     * Check of the url configuration of trafic source
     *
     * @var string
     */
    public $urlCheck;

    /**
     * Creation date
     *
     * @var DateTime
     */
    public $createdAt;
}
