<?php

namespace Webmarketer\Auth;

class AccessTokenResponse
{
    /**
     * @var string
     */
    private $access_token;

    /**
     * @var string|null
     */
    private $token_type;

    /**
     * @var int|null
     */
    private $expires_in;

    /**
     * @var string|null
     */
    private $refresh_token;

    /**
     * @var string|null
     */
    private $scope;

    /**
     * @param string $access_token
     * @param string|null $token_type
     * @param int|null $expires_in
     * @param string|null $scope
     * @param string|null $refresh_token
     */
    public function __construct($access_token, $token_type, $expires_in, $scope, $refresh_token)
    {
        $this->access_token = $access_token;
        $this->token_type = $token_type;
        $this->expires_in = $expires_in;
        $this->scope = $scope;
        $this->refresh_token = $refresh_token;
    }

    /**
     * @return JWT
     */
    public function getAccessToken()
    {
        return new JWT($this->access_token);
    }

    /**
     * @return string|null
     */
    public function getTokenType()
    {
        return $this->token_type;
    }

    /**
     * @return int|null
     */
    public function getExpiresIn()
    {
        return $this->expires_in;
    }

    /**
     * @return string|null
     */
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    /**
     * @return string|null
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return array
     */
    public function getRaw()
    {
        return [
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_in' => $this->expires_in,
            'refresh_token' => $this->refresh_token,
            'scope' => $this->scope
        ];
    }
}