<?php

namespace Webmarketer\Api\Project\Fields;

use DateTime;
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
     * [Required]
     *
     * @var string
     */
    public $entity;

    /**
     * Determine if this field is a PII (RGPD compliant)
     * [Required]
     *
     * @var boolean
     */
    public $isPii;

    /**
     * Field label
     * [Required]
     *
     * @var string
     */
    public $label;

    /**
     * Field key
     * [Required]
     *
     * @var string
     */
    public $key;

    /**
     * Array of key => value field's metadata
     * Recommended to add a from to identify where the field was created
     *
     * @var array
     */
    public $metadata;

    /**
     * Field projectId
     * [Required]
     *
     * @var string
     */
    public $projectId;

    /**
     * Field value type (string, number, phone, currency)
     * [Required]
     *
     * @var string
     */
    public $type;

    /**
     * Determine if this is used for reconciliation
     * [Required]
     *
     * @var boolean
     */
    public $discriminant;

    /**
     * Field archive date
     *
     * @var DateTime | null
     */
    public $archivedAt;
}
