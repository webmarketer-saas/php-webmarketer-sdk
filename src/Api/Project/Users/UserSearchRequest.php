<?php

namespace Webmarketer\Api\Project\Users;

use Webmarketer\Api\AbstractApiObject;

class UserSearchRequest extends AbstractApiObject
{
    /**
     * Fields to retrieve with the user
     *
     * @var string[]
     */
    public $fields = [];

    /**
     * Order result
     * ASC - DESC
     * Default: ASC
     *
     * @var string
     */
    public $order = "ASC";

    /**
     * Sort result must be a user field
     * Default: lastname
     *
     * @var string
     */
    public $sort = "lastname";

    /**
     * Filter users with filter query
     *
     * @var array
     */
    public $filters;

    /**
     * Pagination limit
     *
     * @var int
     */
    public $limit;

    /**
     * Pagination offset
     *
     * @var int
     */
    public $offset;
}
