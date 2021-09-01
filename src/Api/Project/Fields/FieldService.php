<?php

namespace Webmarketer\Api\Project\Fields;

use Exception;
use Webmarketer\Api\ServiceWrapper;

class FieldService extends ServiceWrapper
{
    protected $model = Field::class;

    /**
     * @param array $config call specific SDK configuration
     *
     * @return Field[]
     * @throws Exception
     */
    public function getAll($config = [])
    {
        return $this->api_service->get("projects/{$this->getProjectId($config)}/fields");
    }
}
