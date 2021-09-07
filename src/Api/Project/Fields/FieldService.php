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

    /**
     * @param Field $field
     * @param array $config call specific SDK configuration
     *
     * @throws Exception
     */
    public function create($field, $config = [])
    {
        $this->api_service->post(
            "projects/{$this->getProjectId($config)}/fields",
            $field
        );
    }

    /**
     * @param string $_id
     * @param array $config call specific SDK configuration
     *
     * @throws Exception
     */
    public function deleteById($_id, $config = [])
    {
        $this->api_service->delete("projects/{$this->getProjectId($config)}/fields/$_id");
    }
}
