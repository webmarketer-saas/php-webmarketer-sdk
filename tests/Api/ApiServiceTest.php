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

    public function testPostPaginated()
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
        $post_response = $api_service->post('test', [], true);

        $this->assertInstanceOf(PaginatedApiResponse::class, $post_response);
        $this->assertEquals($post_response->getRaw(), $paginated_response_stub->body);
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

        /** PHP 5.6 compatibility syntax */
        $this->assertTrue(is_array($get_response));
        $this->assertSameSize((array)$array_response_stub->body, $get_response);
        $this->assertEquals(get_class($get_response[0]), $api_service->getModel());
    }

    public function testPostArray()
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
        $post_response = $api_service->post('test', [], false);

        /** PHP 5.6 compatibility syntax */
        $this->assertTrue(is_array($post_response));
        $this->assertSameSize((array)$array_response_stub->body, $post_response);
        $this->assertEquals(get_class($post_response[0]), $api_service->getModel());
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

    public function testPostEntity()
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
        $post_response = $api_service->post('test', [], false);

        $this->assertInstanceOf(\stdClass::class, $post_response);
        $this->assertEquals($post_response->_id, $entity_response_stub->body->_id);
    }
}
