<?php

namespace Webmarketer\Api\Agent;

use Webmarketer\Api\ServiceWrapper;
use Webmarketer\Exception\BadRequestException;
use Webmarketer\Exception\EndpointNotFoundException;
use Webmarketer\Exception\GenericHttpException;
use Webmarketer\Exception\UnauthorizedException;

class AgentService extends ServiceWrapper
{
    protected $model = Agent::class;

    /**
     * @return Agent
     * @throws BadRequestException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     * @throws UnauthorizedException
     */
    public function me()
    {
        return $this->api_service->get('agents/me');
    }
}