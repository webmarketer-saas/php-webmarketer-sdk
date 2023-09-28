<?php

namespace Webmarketer\Auth;

use Exception;
use Webmarketer\Exception\BadRequestException;
use Webmarketer\Exception\CredentialException;
use Webmarketer\Exception\DependencyException;
use Webmarketer\Exception\EndpointNotFoundException;
use Webmarketer\Exception\GenericHttpException;
use Webmarketer\Exception\UnauthorizedException;
use Webmarketer\HttpService\HttpService;

abstract class AbstractAuthProvider
{
    /** @var HttpService */
    protected $http_service;

    /** @var JWT | null */
    private $access_token = null;

    /**
     * Init
     *
     * @param $http_service
     * @return void
     *
     * @throws CredentialException
     * @throws DependencyException
     */
    public function init($http_service)
    {
        $this->http_service = $http_service;
        $this->internalInit();
    }

    /**
     * Init the provider
     *
     * @throws CredentialException
     * @throws DependencyException
     */
    abstract protected function internalInit();

    /**
     * @return JWT
     *
     * @throws Exception
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     */
    abstract protected function negotiateAccessToken();

    /**
     * @return JWT
     *
     * @throws Exception
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     */
    public function getJwt()
    {
        if (is_null($this->access_token) || $this->access_token->isExpired()) {
            $this->access_token = $this->negotiateAccessToken();
        }
        return $this->access_token;
    }
}
