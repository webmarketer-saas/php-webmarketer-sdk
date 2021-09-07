<?php

namespace Webmarketer\Api\Project;

use Exception;
use Webmarketer\Api\ServiceWrapper;

class ProjectService extends ServiceWrapper
{
    protected $model = Project::class;

    /**
     * @return Project[]
     * @throws Exception
     */
    public function getAll()
    {
        return $this->api_service->get("agents/projects");
    }

    /**
     * @param array $config call specific SDK configuration
     *
     * @return Project
     * @throws Exception
     */
    public function getById($config = [])
    {
        return $this->api_service->get("projects/{$this->getProjectId($config)}");
    }
}

