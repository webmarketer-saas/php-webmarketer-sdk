<?php

namespace Webmarketer\Api\Project\Users;

use Exception;
use Webmarketer\Api\PaginatedApiResponse;
use Webmarketer\Api\ServiceWrapper;

class UserService extends ServiceWrapper
{
    protected $model = User::class;

    /**
     * @param UserSearchRequest $searchRequest
     * @param array $config call specific SDK configuration
     *
     * @return PaginatedApiResponse
     * @throws Exception
     */
    public function getAll($search_request, $config = [])
    {
        return $this->api_service->post(
            "projects/{$this->getProjectId($config)}/users/search",
            (array) $search_request,
            true
        );
    }
}
