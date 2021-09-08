<?php

namespace Webmarketer\Api\Project;

use DateTime;
use Webmarketer\Api\AbstractApiObject;

class Project extends AbstractApiObject
{
    /**
     * Project ID, must be unique
     *
     * @var string
     */
    public $id;

    /**
     * Workspace name
     *
     * @var string
     */
    public $name;

    /**
     * Workspace ID of the project
     *
     * @var string
     */
    public $workspaceId;

    /**
     * Determine the preset of the project
     * - e-commerce
     * - simple
     * - long-sales-cycle
     *
     * @var string
     */
    public $mode;

    /**
     * Project picture URL
     *
     * @var string
     */
    public $picture;

    /**
     * Project domain
     *
     * @var string
     */
    public $domain;

    /**
     * Configured currency
     *
     * @var string
     */
    public $currency;

    /**
     * Phone parsing configuration
     *
     * @var array
     */
    public $phoneParsingConfig;

    /**
     * Workspace creation date
     *
     * @var DateTime
     */
    public $createdAt;

    /**
     * Workspace last update date
     *
     * @var DateTime
     */
    public $updatedAt;
}
