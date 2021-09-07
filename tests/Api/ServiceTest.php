<?php

namespace Webmarketer\Tests\Api;

use PHPUnit\Framework\TestCase;
use Webmarketer\Api\ApiService;
use Webmarketer\Exception\ConfigurationException;
use Webmarketer\WebmarketerSdk;

class ServiceTest extends TestCase
{
    public function testServiceDetermineDefaultProjectId()
    {
        $stub_project_id = 'my-project';

        $api_service_mock = $this->createMock(ApiService::class);
        $sdk_mock = $this->createMock(WebmarketerSdk::class);
        $sdk_mock
            ->method('getConfig')
            ->willReturn(['default_project_id' => $stub_project_id]);

        $placeholder_service = new PlaceholderService($api_service_mock, $sdk_mock);
        $service_project_id = $placeholder_service->getSdkProjectId();

        $this->assertEquals($service_project_id, $stub_project_id);
    }

    public function testServiceDetermineOverridedProjectId()
    {
        $stub_project_id = 'my-project';
        $stub_project_id_override = 'my-other-project';

        $api_service_mock = $this->createMock(ApiService::class);
        $sdk_mock = $this->createMock(WebmarketerSdk::class);
        $sdk_mock
            ->method('getConfig')
            ->willReturn(['default_project_id' => $stub_project_id]);

        $placeholder_service = new PlaceholderService($api_service_mock, $sdk_mock);
        $service_project_id = $placeholder_service->getSdkProjectId([
            'project_id' => $stub_project_id_override
        ]);

        $this->assertEquals($service_project_id, $stub_project_id_override);
    }

    public function testServiceDetermineProjectIdWithoutDefaultSet()
    {
        $stub_project_id_override = 'my-other-project';

        $api_service_mock = $this->createMock(ApiService::class);
        $sdk_mock = $this->createMock(WebmarketerSdk::class);
        $sdk_mock
            ->method('getConfig')
            ->willReturn([]);

        $placeholder_service = new PlaceholderService($api_service_mock, $sdk_mock);
        $service_project_id = $placeholder_service->getSdkProjectId([
            'project_id' => $stub_project_id_override
        ]);

        $this->assertEquals($service_project_id, $stub_project_id_override);
    }

    public function testServiceProjectIdWithoutDefaultNorOverrideThrowEx()
    {
        $this->expectException(ConfigurationException::class);

        $api_service_mock = $this->createMock(ApiService::class);
        $sdk_mock = $this->createMock(WebmarketerSdk::class);
        $sdk_mock
            ->method('getConfig')
            ->willReturn([]);

        $placeholder_service = new PlaceholderService($api_service_mock, $sdk_mock);
        $placeholder_service->getSdkProjectId();
    }
}
