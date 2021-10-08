<?php

namespace Webmarketer\Api\Project\Events;

use DateTime;
use Exception;
use Webmarketer\Api\ServiceWrapper;

class EventService extends ServiceWrapper
{
    protected $model = Event::class;

    /**
     * @param Event $event
     * @param array $config call specific SDK configuration
     *
     * @return string
     * @throws Exception
     */
    public function create($event, $config = [])
    {
        $event->projectId = $this->getProjectId($config);
        return $this->api_service->post("events", $event);
    }

    /**
     * @param string $event_id
     * @param string $storage_key storage_key of the field to update
     * @param boolean $value new value of the boolean state
     * @param DateTime | null $date optionally provide a date to antedate value update
     *
     * @throws Exception
     */
    public function upsertEventState($event_id, $storage_key, $value, $date = null, $config = [])
    {
        $project_id = $this->getProjectId($config);
        $payload = ["value" => $value];
        if (!is_null($date)) {
            $payload["date"] = $date;
        }


        $this->api_service->patch(
            "projects/$project_id/events/$event_id/states/$storage_key",
            $payload
        );
    }

    /**
     * @param string $event_id
     * @param string $storage_key storage_key of the field to update
     * @param mixed $value new value of the statistic, value will be automatically formatted
     * @param DateTime | null $date optionally provide a date to antedate value update
     *
     * @throws Exception
     */
    public function upsertEventStatistic($event_id, $storage_key, $value, $date = null, $config = [])
    {
        $project_id = $this->getProjectId($config);
        $payload = ["rawValue" => $value];
        if (!is_null($date)) {
            $payload["date"] = $date;
        }

        $this->api_service->patch(
            "projects/$project_id/events/$event_id/statistics/$storage_key",
            $payload
        );
    }
}