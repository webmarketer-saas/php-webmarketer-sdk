<?php

namespace Webmarketer\Api\Workspace;

use DateTime;
use Webmarketer\Api\AbstractApiObject;

class Workspace extends AbstractApiObject
{
    /**
     * Technical ID
     *
     * @var string
     */
    public $id;

    /**
     * Workspace slug, must be unique
     *
     * @var string
     */
    public $slug;

    /**
     * Workspace name
     *
     * @var string
     */
    public $name;

    /**
     * ID of the billing entity
     *
     * @var string
     */
    public $billingId;

    /**
     * Client ID used by all SA of the workspace
     *
     * @var string
     */
    public $clientId;

    /**
     * Array of SA configured on the workspace
     *
     * @var array
     */
    public $serviceAccounts;

    /**
     * Array of accesses configured on the workspace
     *
     * @var array
     */
    public $accesses;

    /**
     * Array of projects in the workspace
     *
     * @var array
     */
    public $projects;

    /**
     * Array of roles configured on the workspace
     *
     * @var array
     */
    public $roles;

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
