<?php

namespace Webmarketer\Tests\Api;

use Webmarketer\Api\ServiceWrapper;

class PlaceholderService extends ServiceWrapper
{
    protected $model = PlaceholderModel::class;

    public function getSdkProjectId($config = [])
    {
        return $this->getProjectId($config);
    }
}
