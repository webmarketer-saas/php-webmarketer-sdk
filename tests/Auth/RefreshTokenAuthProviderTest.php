<?php

namespace Webmarketer\Tests\Auth;

use PHPUnit\Framework\TestCase;
use Webmarketer\Auth\JWT;
use Webmarketer\Auth\RefreshTokenAuthProvider;
use Webmarketer\Exception\CredentialException;
use Webmarketer\HttpService\HttpService;
use Webmarketer\WebmarketerSdk;

class RefreshTokenAuthProviderTest extends TestCase
{
    public function testMalformedClientIdThrowException()
    {
        $this->expectException(CredentialException::class);

        $auth_provider = new RefreshTokenAuthProvider([
            'refresh_token' => 'test',
            'client_id' => '',
            'client_secret' => 'test'
        ]);
        new WebmarketerSdk([], $auth_provider);
    }

    public function testMalformedClientSecretThrowException()
    {
        $this->expectException(CredentialException::class);

        $auth_provider = new RefreshTokenAuthProvider([
            'refresh_token' => 'test',
            'client_id' => 'test',
            'client_secret' => ''
        ]);
        new WebmarketerSdk([], $auth_provider);
    }

    public function testMalformedRefreshTokenThrowException()
    {
        $this->expectException(CredentialException::class);

        $auth_provider = new RefreshTokenAuthProvider([
            'refresh_token' => '',
            'client_id' => 'test',
            'client_secret' => 'test'
        ]);
        new WebmarketerSdk([], $auth_provider);
    }

    public function testTokenNegotiate()
    {
        $test_client_id = 'clientida';
        $test_client_secret = 'clientseca';
        $test_refresh_token = 'refreshtokena';
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
                    'client_id' => $test_client_id,
                    'client_secret' => $test_client_secret,
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $test_refresh_token,
                    'scope' => 'offline_access'
                ]),
                $this->equalTo([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]),
                $this->equalTo(WebmarketerSdk::getBaseOauthPath())
            );

        $auth_provider = new RefreshTokenAuthProvider([
            'client_id' => $test_client_id,
            'client_secret' => $test_client_secret,
            'refresh_token' => $test_refresh_token
        ]);
        $auth_provider->init($http_service_mock);
        $jwt = $auth_provider->getJwt();

        $this->assertInstanceOf(JWT::class, $jwt);
        $this->assertEquals($test_access_token, "$jwt");
    }
}
