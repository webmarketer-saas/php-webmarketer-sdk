<?php

namespace Webmarketer;

use Exception;
use Webmarketer\Api\Agent\AgentService;
use Webmarketer\Api\ApiService;
use Webmarketer\Api\Project\CustomColumns\CustomColumnService;
use Webmarketer\Api\Project\Events\EventService;
use Webmarketer\Api\Project\EventTypes\EventTypeService;
use Webmarketer\Api\Project\Fields\FieldService;
use Webmarketer\Api\Project\Interactions\InteractionService;
use Webmarketer\Api\Project\ProjectService;
use Webmarketer\Api\Project\TrafficSources\TrafficSourceService;
use Webmarketer\Api\Project\Users\UserService;
use Webmarketer\Api\ServiceWrapper;
use Webmarketer\Api\Workspace\WorkspaceService;
use Webmarketer\Auth\RefreshTokenAuthProvider;
use Webmarketer\Auth\ServiceAccountAuthProvider;
use Webmarketer\Exception\CredentialException;
use Webmarketer\Exception\DependencyException;
use Webmarketer\Exception\BadRequestException;
use Webmarketer\Exception\EndpointNotFoundException;
use Webmarketer\Exception\GenericHttpException;
use Webmarketer\Exception\UnauthorizedException;
use Webmarketer\HttpService\HttpService;
use Webmarketer\Auth\JWT;

/**
 * This is the base class for the Webmarketer SDK.
 */
class WebmarketerSdk
{
    const SDK_VERSION = '2.0.0';
    const API_VERSION = 'v1';
    const BASE_USER_AGENT = 'php-webmarketer-sdk';
    const SDK_DEFAULT_CONFIG = [
        // Default project id
        'default_project_id' => null
    ];

    /** @var ServiceAccountAuthProvider | RefreshTokenAuthProvider */
    private $auth_provider;

    /** @var array */
    private $config;

    /** @var HttpService */
    private $http_service;

    /** @var ServiceWrapper[]  */
    private $services = [];

    /**
     * Construct a new instance of the Webmarketer SDK.
     *
     * @param array $config provide optional config on sdk init (merged with default config)
     * @param ServiceAccountAuthProvider | RefreshTokenAuthProvider | null $auth_provider auth provider used by the SDK to authenticate against Webmarketer API
     *        (if not provided, default will be based on service account and try to load SA based on the WEBMARKETER_APPLICATION_CREDENTIALS env var)
     *
     * @throws DependencyException
     * @throws CredentialException
     */
    public function __construct($config = [], $auth_provider = null)
    {
        $this->auth_provider = is_null($auth_provider)
            ? new ServiceAccountAuthProvider(['scopes' => 'full_access'])
            : $auth_provider;
        $this->auth_provider->init(new HttpService());

        $this->http_service = $this->buildHttpService();
        $this->setConfig($config);
    }

    /**
     * Get SDK configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Update SDK configuration
     *
     * @param $config
     */
    public function setConfig($config)
    {
        $this->config = array_merge(self::SDK_DEFAULT_CONFIG, $config);
    }

    /**
     * @return JWT
     *
     * @throws Exception
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     */
    public function getAccessToken()
    {
        return $this->auth_provider->getJwt();
    }

    /**
     * @return AgentService
     */
    public function getAgentService()
    {
        if (!isset($this->services[AgentService::class])) {
            $this->services[AgentService::class] = new AgentService(
                new ApiService($this->http_service),
                $this
            );
        }
        return $this->services[AgentService::class];
    }

    /**
     * @return WorkspaceService
     */
    public function getWorkspaceService()
    {
        if (!isset($this->services[WorkspaceService::class])) {
            $this->services[WorkspaceService::class] = new WorkspaceService(
                new ApiService($this->http_service),
                $this
            );
        }
        return $this->services[WorkspaceService::class];
    }

    /**
     * @return ProjectService
     */
    public function getProjectService()
    {
        if (!isset($this->services[ProjectService::class])) {
            $this->services[ProjectService::class] = new ProjectService(
                new ApiService($this->http_service),
                $this
            );
        }
        return $this->services[ProjectService::class];
    }

    /**
     * @return EventService
     */
    public function getEventService()
    {
        if (!isset($this->services[EventService::class])) {
            $this->services[EventService::class] = new EventService(
                new ApiService($this->http_service),
                $this
            );
        }
        return $this->services[EventService::class];
    }

    /**
     * @return EventTypeService
     */
    public function getEventTypeService()
    {
        if (!isset($this->services[EventTypeService::class])) {
            $this->services[EventTypeService::class] = new EventTypeService(
                new ApiService($this->http_service),
                $this
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
                $this
            );
        }
        return $this->services[FieldService::class];
    }

    /**
     * @return TrafficSourceService
     */
    public function getTrafficSourceService()
    {
        if (!isset($this->services[TrafficSourceService::class])) {
            $this->services[TrafficSourceService::class] = new TrafficSourceService(
                new ApiService($this->http_service),
                $this
            );
        }
        return $this->services[TrafficSourceService::class];
    }

    /**
     * @return CustomColumnService
     */
    public function getCustomColumnService()
    {
        if (!isset($this->services[CustomColumnService::class])) {
            $this->services[CustomColumnService::class] = new CustomColumnService(
                new ApiService($this->http_service),
                $this
            );
        }
        return $this->services[CustomColumnService::class];
    }

    /**
     * @return UserService
     */
    public function getUserService()
    {
        if (!isset($this->services[UserService::class])) {
            $this->services[UserService::class] = new UserService(
                new ApiService($this->http_service),
                $this
            );
        }
        return $this->services[UserService::class];
    }

    /**
     * @return InteractionService
     */
    public function getInteractionService()
    {
        if (!isset($this->services[InteractionService::class])) {
            $this->services[InteractionService::class] = new InteractionService(
                new ApiService($this->http_service),
                $this
            );
        }
        return $this->services[InteractionService::class];
    }

    /**
     * @return string
     */
    public static function getBaseApiPath()
    {
        return getenv('WEBMARKETER_DEBUG_BASE_API_PATH') ?: 'https://api.webmarketer.io/api';
    }

    /** @return string */
    public static function getBaseOauthPath()
    {
        return getenv('WEBMARKETER_DEBUG_BASE_OAUTH_PATH') ?: 'https://oauth.webmarketer.io/oidc';
    }

    /**
     * Construct and configure HttpService for SDK - Internal
     *
     * @return HttpService
     * @codeCoverageIgnore
     */
    private function buildHttpService()
    {
        $http_service = new HttpService();
        $http_service->addRequestProcessor('req-ua-processor', function ($request) {
            return $request->withHeader('User-Agent', WebmarketerSdk::BASE_USER_AGENT . '/' . WebmarketerSdk::SDK_VERSION);
        });
        $http_service->addRequestProcessor('req-oauth-processor', function ($request) {
            return $request->withHeader('Authorization', 'Bearer ' . $this->auth_provider->getJwt());
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
