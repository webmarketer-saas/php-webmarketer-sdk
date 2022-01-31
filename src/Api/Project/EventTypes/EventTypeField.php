<?php

namespace Webmarketer\Api\Project\EventTypes;

use stdClass;
use Webmarketer\Api\AbstractApiObject;

class EventTypeField extends AbstractApiObject
{
    /**
     * Field entity, can be from project fields :
     *  - user
     *  - state
     *  - statistic
     * Or scoped to event type :
     *  - event-type
     *
     * @var string
     */
    public $entity;

    /**
     * Field type :
     *  - string
     *  - email
     *  - boolean
     *  - date
     *  - location
     *  - currency
     *  - number
     *  - phone
     * if field entity is user, statistic or state, must correspond to project field type
     * [Required]
     *
     * @var string
     */
    public $type;

    /**
     * Determine the key to be retrieved in events for data ingestion
     * if field entity is user, statistic or state, must correspond to project field ingestionKey
     *
     * @var string
     */
    public $ingestionKey;

    /**
     * Determine the unique slug of this field value, must be unique across the project
     * if field entity is user, statistic or state, must correspond to project field storageKey
     *
     * @var string
     */
    public $storageKey;

    /**
     * Determine whether the field is optional in event type or not
     * [Required]
     *
     * @var boolean
     */
    public $optional;

    /**
     * Determine if this field is a PII (RGPD compliant)
     *
     * @var boolean
     */
    public $isPii;

    /**
     * Determine if this is used for reconciliation
     * [Required]
     *
     * @var boolean
     */
    public $discriminant;

    /**
     * @param stdClass | array $payload
     */
    public static function createFromArray($payload)
    {
        $payload = (object) $payload;
        $eventTypeField = parent::createFromArray($payload);
        if (property_exists($payload, "key")) {
            $eventTypeField->ingestionKey = $payload->key;
            $eventTypeField->storageKey = $payload->key;
        }
        return $eventTypeField;
    }
}
