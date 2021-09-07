<?php

namespace Webmarketer\Tests\Api;

use PHPUnit\Framework\TestCase;
use Webmarketer\Api\ApiService;
use Webmarketer\Api\PaginatedApiResponse;
use Webmarketer\HttpService\HttpService;

class ApiServiceTest extends TestCase
{
    public function testGetPaginated()
    {
        $paginated_response_stub = (object) [
            'body' => (object) [
                'count' => 0,
                'data' => [],
                'limit' => 15,
                'offset' => 0,
                'total' => 0
            ],
            'status_code' => 200
        ];

        $http_service_mock = $this->createMock(HttpService::class);
        $http_service_mock
            ->method('sendRequest')
            ->willReturn($paginated_response_stub);

        $api_service = new ApiService($http_service_mock);
        $get_response = $api_service->get('test', [], true);

        $this->assertInstanceOf(PaginatedApiResponse::class, $get_response);
        $this->assertEquals($get_response->getRaw(), $paginated_response_stub->body);
    }

    public function testGetArray()
    {
        $array_response_stub = (object) [
            'body' => [
                ['_id' => 'a1'],
                ['_id' => 'b2'],
                ['_id' => 'c3'],
            ],
            'status_code' => 200
        ];

        $http_service_mock = $this->createMock(HttpService::class);
        $http_service_mock
            ->method('sendRequest')
            ->willReturn($array_response_stub);

        $api_service = new ApiService($http_service_mock);
        $api_service->setModel(PlaceholderModel::class);
        $get_response = $api_service->get('test', [], false);

        $this->assertIsArray($get_response);
        $this->assertSameSize((array)$array_response_stub->body, $get_response);
        $this->assertEquals(get_class($get_response[0]), $api_service->getModel());
    }

    public function testGetEntity()
    {
        $entity_response_stub = (object) [
            'body' => (object) [
                '_id' => 'a1'
            ],
            'status_code' => 200
        ];

        $http_service_mock = $this->createMock(HttpService::class);
        $http_service_mock
            ->method('sendRequest')
            ->willReturn($entity_response_stub);

        $api_service = new ApiService($http_service_mock);
        $api_service->setModel(PlaceholderModel::class);
        $get_response = $api_service->get('test', [], false);

        $this->assertInstanceOf(PlaceholderModel::class, $get_response);
        $this->assertEquals($get_response->_id, $entity_response_stub->body->_id);
    }

    public function testPost()
    {
        $ex = null;
        try {
            $http_service_mock = $this->createMock(HttpService::class);
            $api_service = new ApiService($http_service_mock);
            $api_service->post('test', []);
        } catch (\Exception $ex) {}

        $this->assertNull($ex);
    }

    public function testPatch()
    {
        $ex = null;
        try {
            $http_service_mock = $this->createMock(HttpService::class);
            $api_service = new ApiService($http_service_mock);
            $api_service->patch('test', []);
        } catch (\Exception $ex) {}

        $this->assertNull($ex);
    }

    public function testDelete()
    {
        $ex = null;
        try {
            $http_service_mock = $this->createMock(HttpService::class);
            $api_service = new ApiService($http_service_mock);
            $api_service->delete('test');
        } catch (\Exception $ex) {}

        $this->assertNull($ex);
    }
}
