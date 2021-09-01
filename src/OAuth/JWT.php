<?php

namespace Webmarketer\OAuth;

class JWT
{
    private $jwt;

    private $header;
    private $payload;
    private $signature;

    public function __construct($jwt)
    {
        $this->jwt = $jwt;

        $segs = explode('.', $this->jwt);

        $this->header = json_decode(base64_decode($segs[0]));
        $this->payload = json_decode(base64_decode($segs[1]));
        $this->signature = $segs[2];
    }


    /**
     * Return the jwt expiry (based on "exp" claim)
     *
     * @return bool
     */
    public function isExpired()
    {
        if (property_exists($this->payload, 'exp')) {
            return time() > $this->payload->exp;
        }
        return false;
    }

    public function __toString()
    {
        return $this->jwt;
    }
}
