<?php

namespace Webmarketer\Api;

use Exception;
use stdClass;
use Webmarketer\Exception\BadRequestException;
use Webmarketer\Exception\EndpointNotFoundException;
use Webmarketer\Exception\GenericHttpException;
use Webmarketer\Exception\UnauthorizedException;
use Webmarketer\HttpService\HttpService;

class ApiService
{
    const PER_PAGE = 10;

    /**
     * @var HttpService
     */
    private $http_service;

    /**
     * @var string
     */
    private $model;

    /**
     * @param HttpService $http_service
     */
    public function __construct($http_service)
    {
        $this->http_service = $http_service;
    }

    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @param boolean $is_paginated
     *
     * @return PaginatedApiResponse | AbstractApiObject | AbstractApiObject[]
     *
     * @throws Exception
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     */
    public function get($endpoint, $params = [], $is_paginated = false)
    {
        $url_params = $params;
        if ($is_paginated) {
            $params = array_merge([
                'limit' => self::PER_PAGE,
                'offset' => 0
            ], $params);

            $url_params['limit'] = $params['limit'];
            $url_params['offset'] = $params['offset'];
        }

        $path = $endpoint . '?' . http_build_query($url_params);

        $response = $this->http_service->sendRequest('GET', $path);

        if ($is_paginated) {
            return new PaginatedApiResponse($response->body, $endpoint, $params, $this);
        } elseif (is_array($response->body)) {
            return array_map(function ($item) {
                return call_user_func([$this->model, 'createFromArray'], $item);
            }, $response->body);
        }

        return call_user_func([$this->model, 'createFromArray'], $response->body);
    }

    /**
     * @param string $endpoint
     * @param array $body
     *
     * @return PaginatedApiResponse | AbstractApiObject[] | string
     * @throws Exception
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     */
    public function post($endpoint, $body = [], $is_paginated = false)
    {
        if ($is_paginated) {
            $body['limit'] = key_exists('limit', $body) && $body['limit'] ? $body['limit'] : self::PER_PAGE;
            $body['offset'] = key_exists('offset', $body) && $body['offset'] ? $body['offset'] : 0;
        }

        $response = $this->http_service->sendRequest('POST', $endpoint, $body, [
            'Content-Type' => 'application/json'
        ]);

        if ($is_paginated) {
            return new PaginatedApiResponse($response->body, $endpoint, $body, $this, 'POST');
        } elseif (is_array($response->body)) {
            return array_map(function ($item) {
                return call_user_func([$this->model, 'createFromArray'], $item);
            }, $response->body);
        }

        return $response->body;
    }

    /**
     * @param string $endpoint
     * @param array $body
     *
     * @throws Exception
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     */
    public function patch($endpoint, $body = [])
    {
        $this->http_service->sendRequest('PATCH', $endpoint, $body, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @param string $endpoint
     *
     * @throws Exception
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     */
    public function delete($endpoint)
    {
        $this->http_service->sendRequest('DELETE', $endpoint);
    }
}
