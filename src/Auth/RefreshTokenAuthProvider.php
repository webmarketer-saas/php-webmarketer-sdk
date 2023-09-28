<?php

namespace Webmarketer\Auth;

use Webmarketer\Exception\CredentialException;
use Webmarketer\WebmarketerSdk;

class RefreshTokenAuthProvider extends AbstractAuthProvider
{
    /** @var string */
    private $client_id;

    /** @var string */
    private $client_secret;

    /** @var string */
    private $refresh_token;

    /**
     * Construct an instance of the refresh token based auth
     *
     * @param array{
     *     client_id: string,
     *     client_secret: string,
     *     refresh_token: string
     * } $args
     * client_id : auth application client_id
     * client_secret : auth application client_secret
     * refresh_token : user refresh_token
     */
    public function __construct($args)
    {
        $this->client_id = $args['client_id'];
        $this->client_secret = $args['client_secret'];
        $this->refresh_token = $args['refresh_token'];
    }

    protected function internalInit()
    {
        if (!$this->refresh_token) {
            throw new CredentialException('Credential invalid, refresh token malformed');
        }
        if (!$this->client_secret) {
            throw new CredentialException('Credential invalid, client secret malformed');
        }
        if (!$this->client_id) {
            throw new CredentialException('Credential invalid, client id malformed');
        }
    }

    protected function negotiateAccessToken()
    {
        $response = $this->http_service->sendRequest(
            'POST',
            'token',
            [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->refresh_token,
                'scope' => 'offline_access'
            ],
            [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            WebmarketerSdk::BASE_OAUTH_PATH
        );

        return new JWT($response->body->access_token);
    }
}
