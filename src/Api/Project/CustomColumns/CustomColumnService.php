<?php

namespace Webmarketer\Api\Project\CustomColumns;

use Exception;
use Webmarketer\Api\ServiceWrapper;

class CustomColumnService extends ServiceWrapper
{
    protected $model = CustomColumn::class;

    /**
     * @param array $config call specific SDK configuration
     *
     * @return CustomColumn[]
     * @throws Exception
     */
    public function getAll($config = [])
    {
        return $this->api_service->get("projects/{$this->getProjectId($config)}/custom-columns");
    }

    /**
     * @param $custom_column_id
     * @param array $config call specific SDK configuration
     *
     * @return CustomColumn
     * @throws Exception
     */
    public function getById($custom_column_id, $config = [])
    {
        return $this->api_service->get("projects/{$this->getProjectId($config)}/custom-columns/$custom_column_id");
    }

    /**
     * @param CustomColumn $custom_column
     * @param array $config call specific SDK configuration
     *
     * @throws Exception
     */
    public function create($custom_column, $config = [])
    {
        $this->api_service->post(
            "projects/{$this->getProjectId($config)}/custom-columns",
            $custom_column
        );
    }

    /**
     * @param string $custom_column_id
     * @param array $config call specific SDK configuration
     *
     * @throws Exception
     */
    public function deleteById($custom_column_id, $config = [])
    {
        $this->api_service->delete("projects/{$this->getProjectId($config)}/custom-columns/$custom_column_id");
    }
}
