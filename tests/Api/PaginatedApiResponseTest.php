<?php

namespace Webmarketer\Tests\Api;

use PHPUnit\Framework\TestCase;
use Webmarketer\Api\ApiService;
use Webmarketer\Api\PaginatedApiResponse;

class PaginatedApiResponseTest extends TestCase
{
    protected $payload_stub;
    protected $endpoint_stub;
    protected $paginated_response;

    public function testGetData()
    {
        $this->beforeTest();
        $data = $this->paginated_response->getData();

        /** PHP 5.6 compatibility syntax */
        $this->assertTrue(is_array($data));
        $this->assertSameSize($data, $this->payload_stub->data);
        $this->assertInstanceOf(PlaceholderModel::class, $data[0]);
    }

    public function testGetTotal()
    {
        $this->beforeTest();
        $this->assertEquals($this->payload_stub->total, $this->paginated_response->getTotal());
    }

    public function testGetPerPage()
    {
        $this->beforeTest();
        $this->assertEquals($this->payload_stub->limit, $this->paginated_response->getPerPage());
    }

    public function testGetRaw()
    {
        $this->beforeTest();
        $this->assertEquals($this->payload_stub, $this->paginated_response->getRaw());
    }

    public function testHasNext()
    {
        $this->beforeTest();
        $this->assertFalse($this->paginated_response->hasNext());
    }

    public function testFetchNext()
    {
        $this->beforeTest();
        $this->assertNull($this->paginated_response->fetchNext());
    }

    public function testPaginationIterator()
    {
        $this->beforeTest();
        $iterator = $this->paginated_response->paginationIterator();

        $this->assertEquals($iterator->current()->_id, $this->payload_stub->data[0]['_id']);
        foreach ($iterator as $data) {
            $this->assertInstanceOf(PlaceholderModel::class, $data);
        }
        $this->assertInstanceOf(\Generator::class, $iterator);
    }

    private function beforeTest()
    {
        $this->payload_stub = (object) [
            'count' => 3,
            'data' => [
                ['_id' => 'a1'],
                ['_id' => 'b2'],
                ['_id' => 'c3'],
            ],
            'limit' => 15,
            'offset' => 0,
            'total' => 3
        ];
        $this->endpoint_stub = 'test';

        $api_service_mock = $this->createMock(ApiService::class);
        $api_service_mock
            ->method('getModel')
            ->willReturn(PlaceholderModel::class);

        $this->paginated_response = new PaginatedApiResponse(
            $this->payload_stub,
            $this->endpoint_stub,
            [
                'limit' => 15,
                'offset' => 0
            ],
            $api_service_mock
        );
    }
}
