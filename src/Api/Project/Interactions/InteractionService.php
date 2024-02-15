<?php

namespace Webmarketer\Api\Project\Interactions;

use DateTime;
use Exception;
use Webmarketer\Api\ServiceWrapper;

class InteractionService extends ServiceWrapper
{
    /**
     * @param array $user_data discriminants data of the user that made the interaction (key-value pairs)
     * @param array $node_data parameters to identify the node to attach to the interaction (key-value pairs)
     * @param DateTime | null $date optionally provide a date to antedate the interaction
     * @param $config
     *
     * @throws Exception
     */
    public function createOfflineInteraction($user_data, $node_data, $date = null, $config = [])
    {
        $project_id = $this->getProjectId($config);
        $payload = [
            "userData" => $user_data,
            "nodeData" => $node_data
        ];
        if (!is_null($date)) {
            $payload["date"] = $date;
        }

        $this->api_service->post(
            "projects/$project_id/interactions/offline",
            $payload
        );
    }
}