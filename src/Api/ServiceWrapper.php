<?php

namespace Webmarketer\Api;

use Webmarketer\Exception\ConfigurationException;
use Webmarketer\WebmarketerSdk;

abstract class ServiceWrapper
{
    protected $model = 'notset';

    /** @var ApiService */
    protected $api_service;

    /** @var WebmarketerSdk */
    protected $sdk;

    public function __construct($api_service, $sdk)
    {
        $this->api_service = $api_service;
        $this->sdk = $sdk;

        $api_service->setModel($this->model);
    }

    protected function getProjectId($config = [])
    {
        $config = array_merge_recursive($this->sdk->getConfig(), $config);
        if (!isset($config['default_project_id']) && !isset($config['project_id'])) {
            throw new ConfigurationException("no specified project id (default_project_id or call specific project id)");
        }

        return isset($config['project_id'])
            ? $config['project_id']
            : $config['default_project_id'];
    }
}
