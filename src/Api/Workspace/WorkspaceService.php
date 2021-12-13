<?php

namespace Webmarketer\Api\Workspace;

use Exception;
use Webmarketer\Api\ServiceWrapper;

class WorkspaceService extends ServiceWrapper
{
    protected $model = Workspace::class;

    /**
     * @return Workspace
     * @throws Exception
     */
    public function getCurrentWorkspace()
    {
        // SA belong to only one workspace
        return $this->api_service->get("agents/me/workspaces")[0];
    }
}

