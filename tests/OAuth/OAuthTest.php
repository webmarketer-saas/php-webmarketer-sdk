<?php

namespace Webmarketer\Tests\OAuth;

use PHPUnit\Framework\TestCase;
use Webmarketer\Exception\CredentialException;
use Webmarketer\Exception\UnauthorizedException;
use Webmarketer\HttpService\HttpService;
use Webmarketer\OAuth\JWT;
use Webmarketer\OAuth\OAuth;

class OAuthTest extends TestCase
{
    public function testUndefinedCredentialThrowException()
    {
        $this->expectException(CredentialException::class);

        new OAuth(
            $this->createMock(HttpService::class),
            null,
            '',
            false
        );
    }

    public function testMalformatedCredentialThrowException()
    {
        $this->expectException(CredentialException::class);

        new OAuth(
            $this->createMock(HttpService::class),
            '{}',
            ''
        );
    }

    public function testNotProvidingStringCredentialFallbackToEnvVar()
    {
        $ex = null;
        try {
            new OAuth(
                $this->createMock(HttpService::class),
                null,
                ''
            );
        } catch (\Exception $ex) {}

        $this->assertNull($ex);
    }

    public function testFetchAccessTokenSuccess()
    {
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

        $oauth = new OAuth(
            $http_service_mock,
            null,
            ''
        );
        $jwt = $oauth->fetchAccessTokenWithCredential();

        $this->assertInstanceOf(JWT::class, $jwt);
        $this->assertEquals($test_access_token, "$jwt");
    }

    public function testFetchAccessTokenFailure()
    {
        $this->expectException(UnauthorizedException::class);

        $http_service_mock = $this->createMock(HttpService::class);
        $http_service_mock
            ->method('sendRequest')
            ->willThrowException(new UnauthorizedException());

        $oauth = new OAuth(
            $http_service_mock,
            null,
            ''
        );
        $oauth->fetchAccessTokenWithCredential();
    }
}
