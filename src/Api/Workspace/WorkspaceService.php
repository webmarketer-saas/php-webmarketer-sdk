<?php

namespace Webmarketer\Api\Workspace;

use Exception;
use Webmarketer\Api\ServiceWrapper;

class WorkspaceService extends ServiceWrapper
{
    protected $model = Workspace::class;

    /**
     * Get all available workspaces (when using a SA, SA belongs to only one workspace)
     *
     * @return Workspace[]
     * @throws Exception
     */
    public function getAll()
    {
        return $this->api_service->get("agents/me/workspaces");
    }
}

