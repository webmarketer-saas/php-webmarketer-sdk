<?php

namespace Webmarketer\Tests\OAuth;

use PHPUnit\Framework\TestCase;
use Webmarketer\WebmarketerSdk;

class OauthTest extends TestCase
{
    public function testNegotiateTokenWithEnvVar()
    {
        $sdk = new WebmarketerSdk();
        $oauth_service = $sdk->getOAuthService();
        $token = $oauth_service->fetchAccessTokenWithCredential();
        $this->assertTrue(!is_null($token));
    }

    public function testNegotiateTokenWithString()
    {
        $sdk = new WebmarketerSdk([
            'credential' => file_get_contents('/usr/src/app/secrets/dev-sa.json')
        ]);
        $oauth_service = $sdk->getOAuthService();
        $token = $oauth_service->fetchAccessTokenWithCredential();
        $this->assertTrue(!is_null($token));
    }
}
