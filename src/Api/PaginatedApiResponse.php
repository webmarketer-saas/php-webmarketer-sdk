<?php

namespace Webmarketer\Api;

use Generator;
use stdClass;

/**
 * @template T of AbstractApiObject
 */
class PaginatedApiResponse
{
    /** @var stdClass */
    private $payload;

    /** @var ApiService */
    private $service;

    /** @var string */
    private $endpoint;

    /** @var string */
    private $method;

    /** @var array */
    private $params;

    /**
     * @param stdClass $payload
     * @param string $endpoint
     * @param array $params
     * @param ApiService $service
     */
    public function __construct($payload, $endpoint, $params, $service, $method = 'GET')
    {
        $this->payload = $payload;
        $this->service = $service;
        $this->endpoint = $endpoint;
        $this->params = $params;
        $this->method = $method;
    }

    /**
     * get array of data model
     *
     * @return array[]
     */
    public function getData()
    {
        return array_map(function ($datum) {
            $model = $this->service->getModel();
            return call_user_func([$model, 'createFromArray'], $datum);
        }, $this->payload->data);
    }

    /**
     * get pagination total items
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->payload->total;
    }

    /**
     * get number of items per page
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->payload->limit;
    }

    /**
     * get plain object payload as received from API
     *
     * @return stdClass
     */
    public function getRaw()
    {
        return $this->payload;
    }

    /**
     * determine if there is a next page pagination
     *
     * @return bool
     */
    public function hasNext()
    {
        return $this->payload->offset + $this->payload->limit < $this->payload->total;
    }

    /**
     * fetch the next pagination page
     *
     * @return PaginatedApiResponse<T>
     * @throws \Exception
     */
    public function fetchNext()
    {
        $next_page_params = $this->params;
        $next_page_params['offset'] += $next_page_params['limit'];

        return $this->method === 'POST' ?
            $this->service->post($this->endpoint, $next_page_params, true) :
            $this->service->get($this->endpoint, $next_page_params, true);
    }

    /**
     * get an iterator that iterate over all pages
     *
     * @return Generator
     * @throws \Exception
     */
    public function paginationIterator()
    {
        $page = $this;
        do {
            foreach ($page->getData() as $model) {
                yield $model;
            }
            $page = $page->fetchNext();
        } while ($page && $page->hasNext());
    }
}
