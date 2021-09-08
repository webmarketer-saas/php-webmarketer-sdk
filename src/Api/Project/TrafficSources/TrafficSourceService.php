<?php

namespace Webmarketer\Api\Project\TrafficSources;

use Exception;
use Webmarketer\Api\PaginatedApiResponse;
use Webmarketer\Api\ServiceWrapper;

class TrafficSourceService extends ServiceWrapper
{
    protected $model = TrafficSource::class;

    /**
     * @param array $params
     * @param array $config call specific SDK configuration
     *
     * @return PaginatedApiResponse
     * @throws Exception
     */
    public function getAll($params = [], $config = [])
    {
        return $this->api_service->get(
            "projects/{$this->getProjectId($config)}/integrations",
            $params,
            true
        );
    }

    /**
     * @param string $wmkt_id
     * @param array $config call specific SDK configuration
     *
     * @return TrafficSource
     * @throws Exception
     */
    public function getByWmktId($wmkt_id, $config = [])
    {
        return $this->api_service->get("projects/{$this->getProjectId($config)}/integrations/$wmkt_id");
    }
}