<?php

namespace Webmarketer\Api\Project\Users;

use DateTime;
use Webmarketer\Api\AbstractApiObject;

class User extends AbstractApiObject
{
    /**
     * Technical id
     *
     * @var string
     */
    public $_id;

    /**
     * Project ID
     *
     * @var string
     */
    public $projectId;

    /**
     * Event fields
     *
     * @var array
     */
    public $fields;

    /**
     * Date of the last user interaction
     *
     * @var DateTime
     */
    public $lastInteractionDate;

    /**
     * Date of the first user interaction
     *
     * @var DateTime
     */
    public $firstInteractionDate;

    /**
     * Date of the last user event
     *
     * @var DateTime
     */
    public $lastEventDate;

    /**
     * Date of the first user event
     *
     * @var DateTime
     */
    public $firstEventDate;

    /**
     * Creation date
     *
     * @var DateTime
     */
    public $createdAt;

    /**
     * Update date
     *
     * @var DateTime
     */
    public $updatedAt;
}
