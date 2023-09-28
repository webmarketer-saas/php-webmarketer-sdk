<?php

namespace Webmarketer\Api\Agent;

use Webmarketer\Api\AbstractApiObject;

class Agent extends AbstractApiObject
{
    /**
     * Agent UUID
     *
     * @var string
     */
    public $id;

    /**
     * Agent email (unique)
     *
     * @var string
     */
    public $email;

    /**
     * Agent firstname
     *
     * @var string
     */
    public $firstname;

    /**
     * Agent lastname
     *
     * @var string
     */
    public $lastname;

    /**
     * Agent phone number
     *
     * @var string
     */
    public $phone;

    /**
     * Agent picture
     *
     * @var string | null
     */
    public $picture;
}