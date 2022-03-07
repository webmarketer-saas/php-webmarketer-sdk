<?php

namespace Webmarketer\Tests;

use PHPUnit\Framework\TestCase;
use Webmarketer\Api\Project\CustomColumns\CustomColumnService;
use Webmarketer\Api\Project\Events\EventService;
use Webmarketer\Api\Project\EventTypes\EventTypeService;
use Webmarketer\Api\Project\Fields\FieldService;
use Webmarketer\Api\Project\ProjectService;
use Webmarketer\Api\Project\TrafficSources\TrafficSourceService;
use Webmarketer\Api\Project\Users\UserService;
use Webmarketer\Api\Workspace\WorkspaceService;
use Webmarketer\OAuth\OAuth;
use Webmarketer\WebmarketerSdk;

class WebmarketerSdkTest extends TestCase
{
    const SDK_DEFAULT_CONFIG = [
        'credential' => null,
        'scopes' => '',
        'access_token' => null,
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
            'credential' => null,
            'scopes' => 'test-sdk-set-config',
        ];

        $sdk = new WebmarketerSdk();
        $init_oauth_service = $sdk->getOAuthService();
        $sdk->setConfig($config);
        $changed_config_oauth_service = $sdk->getOAuthService();

        $this->assertEquals($sdk->getConfig(), array_merge(
            self::SDK_DEFAULT_CONFIG,
            $config
        ));
        $this->assertNotEquals($init_oauth_service, $changed_config_oauth_service);
    }

    public function testProvideConfigAccessToken()
    {
        $access_token_stub = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
        $sdk = new WebmarketerSdk([
            'access_token' => $access_token_stub
        ]);

        $this->assertEquals("{$sdk->getAccessToken()}", $access_token_stub);
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

    public function testGetUserService()
    {
        $sdk = new WebmarketerSdk();
        $this->assertInstanceOf(UserService::class, $sdk->getUserService());
    }
}
