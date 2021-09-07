<?php

namespace Webmarketer\Api\Project;

use DateTime;
use stdClass;
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
     * Workspace of the project
     *
     * @var stdClass
     */
    public $workspace;

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
     * Array of accesses configured on the workspace
     *
     * @var array
     */
    public $accesses;

    /**
     * Array of custom columns
     *
     * @var array
     */
    public $customColumns;

    /**
     * Array of dashboards
     *
     * @var array
     */
    public $dashboards;

    /**
     * Array of dashboard widgets
     *
     * @var array
     */
    public $dashboardWidgets;

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
     * State of the project initialisation
     *
     * @var stdClass
     */
    public $initState;

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
