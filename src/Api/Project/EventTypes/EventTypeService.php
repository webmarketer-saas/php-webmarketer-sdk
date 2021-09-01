<?php

namespace Webmarketer\Api\Project\EventTypes;

use Exception;
use Webmarketer\Api\PaginatedApiResponse;
use Webmarketer\Api\ServiceWrapper;

class EventTypeService extends ServiceWrapper
{
    protected $model = EventType::class;

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
            "projects/{$this->getProjectId($config)}/event-types",
            $params,
            true
        );
    }

    /**
     * @param string $event_ref
     * @param array $config call specific SDK configuration
     *
     * @return EventType
     * @throws Exception
     */
    public function getByReference($event_ref, $config = [])
    {
        return $this->api_service->get("projects/{$this->getProjectId($config)}/event-types/$event_ref");
    }

    /**
     * @param EventType $event_type
     * @param array $config call specific SDK configuration
     *
     * @throws Exception
     */
    public function create($event_type, $config = [])
    {
        $this->api_service->post(
            "projects/{$this->getProjectId($config)}/event-types",
            $event_type
        );
    }

    /**
     * @param $event_ref
     * @param array $config call specific SDK configuration
     *
     * @throws Exception
     */
    public function deleteByReference($event_ref, $config = [])
    {
        $this->api_service->delete("projects/{$this->getProjectId($config)}/event-types/$event_ref");
    }
}
