<?php

namespace Webmarketer\Tests;

use PHPUnit\Framework\TestCase;
use Webmarketer\Api\Project\CustomColumns\CustomColumnService;
use Webmarketer\Api\Project\Events\EventService;
use Webmarketer\Api\Project\EventTypes\EventTypeService;
use Webmarketer\Api\Project\Fields\FieldService;
use Webmarketer\Api\Project\ProjectService;
use Webmarketer\Api\Project\TrafficSources\TrafficSourceService;
use Webmarketer\Api\Workspace\WorkspaceService;
use Webmarketer\OAuth\OAuth;
use Webmarketer\WebmarketerSdk;

class WebmarketerSdkTest extends TestCase
{
    public function testDefaultConfig()
    {
        $expected_default_configuration = [
            'credential' => null,
            'scopes' => ''
        ];

        $sdk = new WebmarketerSdk();
        $default_config = $sdk->getConfig();

        /** PHP 5.6 compatibility syntax */
        $this->assertTrue(is_array($default_config));
        $this->assertSame($default_config, $expected_default_configuration);
    }

    public function testMergeConfig()
    {
        $overrided_default_configuration = [
            'credential' => '{ "privateKey": "", "privateKeyId": "", "clientId": "", "serviceAccountEmail": "" }',
            'scopes' => 'test'
        ];

        $sdk = new WebmarketerSdk($overrided_default_configuration);
        $config = $sdk->getConfig();

        /** PHP 5.6 compatibility syntax */
        $this->assertTrue(is_array($config));
        $this->assertSame($config, $overrided_default_configuration);
    }

    public function testSdkSetConfig()
    {
        $config = [
            'credential' => null,
            'scopes' => 'test-sdk-set-config'
        ];

        $sdk = new WebmarketerSdk();
        $init_oauth_service = $sdk->getOAuthService();
        $sdk->setConfig($config);
        $changed_config_oauth_service = $sdk->getOAuthService();

        $this->assertEquals($sdk->getConfig(), $config);
        $this->assertNotEquals($init_oauth_service, $changed_config_oauth_service);
    }

    public function testGetOAuthService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(OAuth::class, $sdk->getOAuthService());
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
}
