<?php

namespace Webmarketer\Tests;

use PHPUnit\Framework\TestCase;
use Webmarketer\Api\Agent\AgentService;
use Webmarketer\Api\Project\CustomColumns\CustomColumnService;
use Webmarketer\Api\Project\Events\EventService;
use Webmarketer\Api\Project\EventTypes\EventTypeService;
use Webmarketer\Api\Project\Fields\FieldService;
use Webmarketer\Api\Project\ProjectService;
use Webmarketer\Api\Project\TrafficSources\TrafficSourceService;
use Webmarketer\Api\Project\Users\UserService;
use Webmarketer\Api\Workspace\WorkspaceService;
use Webmarketer\Auth\OAuth;
use Webmarketer\WebmarketerSdk;

class WebmarketerSdkTest extends TestCase
{
    const SDK_DEFAULT_CONFIG = [
        'default_project_id' => null
    ];

    public function testDefaultConfig()
    {
        $sdk = new WebmarketerSdk();
        $default_config = $sdk->getConfig();

        /** PHP 5.6 compatibility syntax */
        $this->assertTrue(is_array($default_config));
        $this->assertSame($default_config, self::SDK_DEFAULT_CONFIG);
    }

    public function testMergeConfig()
    {
        $overrided_default_configuration = [
            'credential' => '{ "privateKey": "", "privateKeyId": "", "clientId": "", "serviceAccountEmail": "" }',
            'scopes' => 'test',
        ];

        $sdk = new WebmarketerSdk($overrided_default_configuration);
        $config = $sdk->getConfig();

        /** PHP 5.6 compatibility syntax */
        $this->assertTrue(is_array($config));
        $this->assertSame($config, array_merge(
            self::SDK_DEFAULT_CONFIG,
            $overrided_default_configuration
        ));
    }

    public function testSdkSetConfig()
    {
        $config = [
            'default_project_id' => 'test-sdk-project-id',
        ];

        $sdk = new WebmarketerSdk();
        $sdk->setConfig($config);

        $this->assertEquals($sdk->getConfig(), array_merge(
            self::SDK_DEFAULT_CONFIG,
            $config
        ));
    }

    public function testGetAgentService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(AgentService::class, $sdk->getAgentService());
    }

    public function testGetWorkspaceService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(WorkspaceService::class, $sdk->getWorkspaceService());
    }

    public function testGetProjectService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(ProjectService::class, $sdk->getProjectService());
    }

    public function testGetEventService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(EventService::class, $sdk->getEventService());
    }

    public function testGetEventTypeService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(EventTypeService::class, $sdk->getEventTypeService());
    }

    public function testGetFieldService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(FieldService::class, $sdk->getFieldService());
    }

    public function testGetTrafficSourceService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(TrafficSourceService::class, $sdk->getTrafficSourceService());
    }

    public function testGetCustomColumnService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(CustomColumnService::class, $sdk->getCustomColumnService());
    }

    public function testGetUserService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(UserService::class, $sdk->getUserService());
    }
}
