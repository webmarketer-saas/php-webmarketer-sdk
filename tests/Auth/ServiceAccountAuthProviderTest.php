<?php

namespace Webmarketer\Tests\Auth;

use PHPUnit\Framework\TestCase;
use Webmarketer\Auth\JWT;
use Webmarketer\Auth\ServiceAccountAuthProvider;
use Webmarketer\Exception\CredentialException;
use Webmarketer\HttpService\HttpService;
use Webmarketer\Utils\Url;
use Webmarketer\WebmarketerSdk;

class ServiceAccountAuthProviderTest extends TestCase
{
    public function testMalformedCredentialThrowException()
    {
        $this->expectException(CredentialException::class);

        $auth_provider = new ServiceAccountAuthProvider([
            'credential' => '{}',
            'scopes' => 'test'
        ]);
        new WebmarketerSdk([], $auth_provider);
    }

    public function testNullCredentialFallbackToEnvVar()
    {
        $catched_ex = null;
        try {
            $auth_provider = new ServiceAccountAuthProvider([
                'credential' => null,
                'scopes' => 'test'
            ]);
            new WebmarketerSdk([], $auth_provider);
        } catch (\Exception $ex) {
            $catched_ex = $ex;
        }

        $this->assertNull($catched_ex);
    }

    public function testNotProvidedCredentialFallbackToEnvVar()
    {
        $catched_ex = null;
        try {
            $auth_provider = new ServiceAccountAuthProvider([
                'scopes' => 'test'
            ]);
            new WebmarketerSdk([], $auth_provider);
        } catch (\Exception $ex) {
            $catched_ex = $ex;
        }

        $this->assertNull($catched_ex);
    }

    public function testTokenNegotiate()
    {
        $test_sa = $this->getTestSA();
        $test_access_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
        $access_token_response_stub = (object) [
            'body' => (object) [
                'access_token' => $test_access_token
            ],
            'status_code' => 200
        ];

        $http_service_mock = $this->createMock(HttpService::class);
        $http_service_mock
            ->method('sendRequest')
            ->willReturn($access_token_response_stub);
        $http_service_mock->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo('POST'),
                $this->equalTo('token'),
                $this->equalTo([
                    'grant_type' => ServiceAccountAuthProvider::JWT_GRANT_TYPE,
                    'client_id' => $test_sa['clientId'],
                    'assertion' => $this->constructTokenAssertion($test_sa),
                ]),
                $this->equalTo([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]),
                $this->equalTo(WebmarketerSdk::BASE_OAUTH_PATH)
            );

        $auth_provider = new ServiceAccountAuthProvider(['scopes' => 'test']);
        $auth_provider->init($http_service_mock);
        $jwt = $auth_provider->getJwt();

        $this->assertInstanceOf(JWT::class, $jwt);
        $this->assertEquals($test_access_token, "$jwt");
    }

    private function getTestSA()
    {
        $path = getenv(ServiceAccountAuthProvider::ENV_VAR_NAME);
        if (empty($path) || !file_exists($path)) {
            return null;
        }

        $json_key = file_get_contents($path);
        return json_decode($json_key, true);
    }

    private function constructTokenAssertion($sa)
    {
        $header = (object) [
            'alg' => ServiceAccountAuthProvider::ALG,
            'typ' => ServiceAccountAuthProvider::TYPE,
            'kid' => $sa['privateKeyId']
        ];

        // construct JWT payload
        $payload = (object) [
            'iss' => $sa['clientId'],
            'sub' => $sa['serviceAccountEmail'],
            'aud' => join('/', [WebmarketerSdk::BASE_OAUTH_PATH, 'token']),
            'scope' => 'test',
            // 5 minutes is enough to negotiate an access token with the oauth server
            'exp' => time() + 60 * 5
        ];

        $jwt = join('.', [
            Url::base64Encode(json_encode($header)),
            Url::base64Encode(json_encode($payload))
        ]);

        // construct and sign JWT signature
        openssl_sign($jwt, $signature, $sa['privateKey'], 'RSA-SHA256');
        $jwt .= '.' . Url::base64Encode($signature);
        return $jwt;
    }
}