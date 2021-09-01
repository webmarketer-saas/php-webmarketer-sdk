<?php

namespace Webmarketer\Api\Project\Fields;

use Webmarketer\Api\AbstractApiObject;

class Field extends AbstractApiObject
{
    /**
     * Technical id
     *
     * @var string
     */
    public $_id;

    /**
     * Field type (one of user, metric or state)
     *
     * @var string
     */
    public $entity;

    /**
     * Field label
     *
     * @var string
     */
    public $label;

    /**
     * Field key
     *
     * @var string
     */
    public $key;

    /**
     * Field projectId
     *
     * @var string
     */
    public $projectId;

    /**
     * Field value type (string, number, phone, currency)
     *
     * @var string
     */
    public $type;

    /**
     * Determine if this is used for reconciliation
     *
     * @var boolean
     */
    public $discriminant;

    /**
     * Determine if this field is optional in eventType
     *
     * @var boolean
     */
    public $optional;
}
