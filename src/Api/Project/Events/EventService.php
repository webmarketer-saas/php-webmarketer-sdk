<?php

namespace Webmarketer\Api\Project\Events;

use Exception;
use Webmarketer\Api\ServiceWrapper;

class EventService extends ServiceWrapper
{
    protected $model = Event::class;

    /**
     * @param Event $event
     * @param array $config call specific SDK configuration
     *
     * @throws Exception
     */
    public function create($event, $config = [])
    {
        $event->projectId = $this->getProjectId($config);
        $this->api_service->post("events", $event);
    }

    /**
     * @param string $event_id Event ID
     * @param EventAddOperation[] $add_operations
     *
     * @throws Exception
     */
    public function addEventData($event_id, $add_operations)
    {
        $this->api_service->patch(
            "events/{$event_id}",
            $add_operations
        );
    }

    /**
     * @param string $event_id Event ID
     * @param EventReplaceOperation[] $replace_operations
     *
     * @throws Exception
     */
    public function replaceEventData($event_id, $replace_operations)
    {
        $this->api_service->patch(
            "events/{$event_id}",
            $replace_operations
        );
    }

    /**
     * @param string $event_id Event ID
     * @param EventRemoveOperation[] $remove_operations
     *
     * @throws Exception
     */
    public function removeEventData($event_id, $remove_operations)
    {
        $this->api_service->patch(
            "events/{$event_id}",
            $remove_operations
        );
    }
}