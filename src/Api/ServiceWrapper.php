<?php

namespace Webmarketer\Api;

use Webmarketer\Exception\ConfigurationException;

abstract class ServiceWrapper
{
    protected $model = 'notset';

    /** @var ApiService */
    protected $api_service;

    protected $config;

    public function __construct($api_service, $config)
    {
        $this->api_service = $api_service;
        $this->config = $config;

        $api_service->setModel($this->model);
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    protected function getProjectId($config = [])
    {
        $config = array_merge_recursive($this->config, $config);
        if (!isset($config['default_project_id']) && !isset($config['project_id'])) {
            throw new ConfigurationException("no specified project id (default_project_id or call specific project id)");
        }

        return isset($config['project_id'])
            ? $config['project_id']
            : $config['default_project_id'];
    }
}
