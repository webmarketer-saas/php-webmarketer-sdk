<?php

namespace Webmarketer;

use Webmarketer\Api\ApiService;
use Webmarketer\Api\Project\EventTypes\EventTypeService;
use Webmarketer\Api\Project\Fields\FieldService;
use Webmarketer\Api\ServiceWrapper;
use Webmarketer\Exception\CredentialException;
use Webmarketer\Exception\DependencyException;
use Webmarketer\Exception\BadRequestException;
use Webmarketer\Exception\EndpointNotFoundException;
use Webmarketer\Exception\GenericHttpException;
use Webmarketer\Exception\UnauthorizedException;
use Webmarketer\HttpService\HttpService;
use Webmarketer\OAuth\JWT;
use Webmarketer\OAuth\OAuth;

/**
 * This is the base class for the Webmarketer SDK.
 */
class WebmarketerSdk
{
    const SDK_VERSION = '0.0.1';
    const API_VERSION = 'v1';
    const BASE_USER_AGENT = 'php-webmarketer-sdk';
    const BASE_API_PATH = 'https://api.webmarketer-staging.me/api';
    const BASE_OAUTH_PATH = 'https://oauth.webmarketer-staging.me/oidc';

    /** @var OAuth */
    private $oauth;

    /** @var array */
    private $config;

    /** @var HttpService */
    private $http_service;

    /** @var JWT | null */
    private $access_token;

    /** @var ServiceWrapper[]  */
    private $services = [];

    /**
     * Construct a new instance of the Webmarketer SDK.
     *
     * @param array $config provide optional config on sdk init (merged with default config)
     *
     * @throws DependencyException
     * @throws CredentialException
     */
    public function __construct($config = [])
    {
        $this->checkRequiredDependencies();

        $this->config = array_merge(
            [
                // JSON credential or path to JSON file
                // if null, SDK try to get JSON file from path in WEBMARKETER_APPLICATION_CREDENTIALS env var
                'credential' => null,
                // Scopes, string, space separated
                'scopes' => '',
            ],
            $config
        );

        $this->http_service = $this->buildHttpService();

        $this->oauth = new OAuth(
            $this->http_service,
            $this->config['credential'],
            $this->config['scopes']
        );
    }

    /**
     * Update SDK configuration
     *
     * @param $config
     *
     * @throws CredentialException
     */
    public function setConfig($config)
    {
        $this->config = $config;

        foreach ($this->services as $service_key => $service) {
            $service->setConfig($this->config);
        }

        $this->oauth = new OAuth(
            $this->http_service,
            $this->config['credential'],
            $this->config['scopes']
        );
    }

    /**
     * @return OAuth
     */
    public function getOAuthService()
    {
        return $this->oauth;
    }

    /**
     * @return EventTypeService
     */
    public function getEventTypeService()
    {
        if (!isset($this->services[EventTypeService::class])) {
            $this->services[EventTypeService::class] = new EventTypeService(
                new ApiService($this->http_service),
                $this->config
            );
        }
        return $this->services[EventTypeService::class];
    }

    /**
     * @return FieldService
     */
    public function getFieldService()
    {
        if (!isset($this->services[FieldService::class])) {
            $this->services[FieldService::class] = new FieldService(
                new ApiService($this->http_service),
                $this->config
            );
        }
        return $this->services[FieldService::class];
    }

    /**
     * Check if required dependencies are available
     *
     * @throws DependencyException
     */
    private function checkRequiredDependencies()
    {
        if (!function_exists('openssl_sign') || !in_array('RSA-SHA256', openssl_get_md_methods(true))) {
            throw new DependencyException('Missing crypto function openssl_sign() or signing alg RSA-SHA256');
        }
    }

    /**
     * Construct and configure HttpService for SDK - Internal
     *
     * @return HttpService
     */
    private function buildHttpService()
    {
        $http_service = new HttpService();
        $http_service->addRequestProcessor('req-ua-processor', function ($request) {
            return $request->withHeader('User-Agent', WebmarketerSdk::BASE_USER_AGENT . '/' . WebmarketerSdk::SDK_VERSION);
        });
        $http_service->addRequestProcessor('req-oauth-processor', function ($request) {
            if (is_null($this->access_token) || $this->access_token->isExpired()) {
                $this->access_token = $this->oauth->fetchAccessTokenWithCredential();
            }
            return $request->withHeader('Authorization', 'Bearer ' . $this->access_token);
        });

        $http_service->addResponseProcessor('res-generic-processor', function ($response) {
            $response_status_code = $response->getStatusCode();
            switch ($response_status_code) {
                case 200:
                case 201:
                    return $response;
                case 400:
                    throw new BadRequestException(HttpService::getResponseErrorMessage($response));
                case 401:
                    throw new UnauthorizedException(HttpService::getResponseErrorMessage($response));
                case 404:
                    throw new EndpointNotFoundException(HttpService::getResponseErrorMessage($response));
                default:
                    throw new GenericHttpException(HttpService::getResponseErrorMessage($response));
            }
        });
        return $http_service;
    }
}
