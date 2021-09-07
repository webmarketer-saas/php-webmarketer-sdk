<?php

namespace Webmarketer\Tests\OAuth;

use PHPUnit\Framework\TestCase;
use Webmarketer\OAuth\JWT;

class JWTTest extends TestCase
{
    public function testIsExpired()
    {
        $jwt_expired = new JWT($this->getJwtStub(strtotime("+2 minutes")));
        $jwt_not_expired = new JWT($this->getJwtStub(strtotime("-2 minutes")));

        $this->assertFalse($jwt_expired->isExpired());
        $this->assertTrue($jwt_not_expired->isExpired());
    }

    public function testToString()
    {
        $jwt_stub = $this->getJwtStub(strtotime("-2 minutes"));

        $jwt = new JWT($jwt_stub);

        $this->assertEquals($jwt_stub, $jwt->__toString());
    }

    private function getJwtStub($exp)
    {
        return join(".", [
            'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9',
            base64_encode(json_encode([
                'exp' => $exp
            ])),
            'SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c'
        ]);
    }
}
