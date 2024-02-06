<?php

namespace Webmarketer\HttpService;

use Exception;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Psr\Http\Message\ResponseInterface;
use stdClass;
use Webmarketer\Exception\BadRequestException;
use Webmarketer\Exception\EndpointNotFoundException;
use Webmarketer\Exception\GenericHttpException;
use Webmarketer\Exception\UnauthorizedException;
use Webmarketer\WebmarketerSdk;

class HttpService
{
    /**
     * @var HttpClient | null
     */
    private $http_client;

    private $request_processors = [];

    private $response_processors = [];

    public function addRequestProcessor($key, $callable)
    {
        $this->request_processors[$key] = $callable;
    }

    public function addResponseProcessor($key, $callable)
    {
        $this->response_processors[$key] = $callable;
    }

    /**
     * Construct a new HttpService that wrap http requests logic
     *
     * @param HttpClient | null $http_client
     */
    public function __construct($http_client = null)
    {
        $this->http_client = $http_client;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array | null $body
     * @param array $headers
     * @param string | null $base_url
     *
     * @return stdClass
     * @throws Exception
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     *
     */
    public function sendRequest($method, $endpoint, $body = null, $headers = [], $base_url = null, $skip_processors = [])
    {
        if ($body) {
            $body = key_exists('Content-Type', $headers) && $headers['Content-Type'] === 'application/x-www-form-urlencoded' ?
                http_build_query($body) :
                json_encode($body);
        }
        $endpoint = is_null($base_url) ? $this->getEndpointUrl($endpoint) : $base_url . '/' . $endpoint;
        $request = MessageFactoryDiscovery::find()->createRequest($method, $endpoint, $headers, $body);
        foreach ($this->request_processors as $key => $rp) {
            if (!in_array($key, $skip_processors)) {
                $request = call_user_func($rp, $request);
            }
        }

        $response = $this->getHttpClient()->sendRequest($request);

        foreach ($this->response_processors as $key => $rp) {
            if (!in_array($key, $skip_processors)) {
                $response = call_user_func($rp, $response);
            }
        }
        return $this->handleHttpResponse($response);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return stdClass
     * @throws UnauthorizedException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     *
     * @throws BadRequestException
     */
    private function handleHttpResponse($response)
    {
        $data_string = $response->getBody()->getContents();
        $json_data = json_decode($data_string, false);
        return (object) [
            'body' => is_null($json_data) ? $data_string : $json_data,
            'status_code' => $response->getStatusCode(),
        ];
    }

    /**
     * @param ResponseInterface $response
     *
     * @return string
     */
    public static function getResponseErrorMessage($response)
    {
        $json_data = json_decode($response->getBody()->getContents());
        return json_last_error() === JSON_ERROR_NONE && isset($json_data) && property_exists($json_data, 'data') ?
            json_encode($json_data->data) :
            '';
    }

    /**
     * @param string $endpoint
     *
     * @return string
     */
    private function getEndpointUrl($endpoint)
    {
        return join('/', [
            WebmarketerSdk::getBaseApiPath(),
            WebmarketerSdk::API_VERSION,
            $endpoint
        ]);
    }

    /**
     * Get provided HttpClient or discover it if no one provided
     *
     * @return HttpClient
     */
    private function getHttpClient()
    {
        if (is_null($this->http_client)) {
            $this->http_client = HttpClientDiscovery::find();
        }

        return $this->http_client;
    }
}
