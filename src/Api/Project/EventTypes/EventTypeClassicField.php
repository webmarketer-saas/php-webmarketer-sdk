<?php

namespace Webmarketer\Api\Project\EventTypes;

class EventTypeClassicField extends AbstractEventTypeField
{
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
     * [Required]
     *
     * @var string
     */
    public $type;

    /**
     * Field key, must be a slug
     * [Required]
     *
     * @var string
     */
    public $key;

    /**
     * Field label
     * [Required]
     *
     * @var string
     */
    public $label;
}
