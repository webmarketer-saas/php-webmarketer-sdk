<?php

namespace Webmarketer\Auth;

use Webmarketer\Exception\BadRequestException;
use Webmarketer\Exception\CredentialException;
use Webmarketer\Exception\EndpointNotFoundException;
use Webmarketer\Exception\GenericHttpException;
use Webmarketer\Exception\OauthInvalidToken;
use Webmarketer\Exception\UnauthorizedException;
use Webmarketer\WebmarketerSdk;

class RefreshTokenAuthProvider extends AbstractAuthProvider
{
    const NEED_USER_INTERACTION = true;

    /** @var string */
    private $client_id;

    /** @var string */
    private $client_secret;

    /** @var string */
    private $refresh_token;

    /** @var AccessTokenResponse | null */
    private $refresh_token_response = null;

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

    /**
     * Perform the refresh token flow with the provider credentials
     * /!\ refresh_token flow can provide a new refresh_token in response that should be stored on your side
     *
     * @return array
     *
     * @throws BadRequestException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     * @throws UnauthorizedException
     */
    public function refreshToken()
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
            WebmarketerSdk::getBaseOauthPath()
        );

        $access_token_response = new AccessTokenResponse(
            $response->body->access_token,
            $response->body->token_type,
            $response->body->expires_in,
            $response->body->scope,
            $response->body->refresh_token
        );

        $this->refresh_token_response = $access_token_response;
        return $access_token_response->getRaw();
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
        if (is_null($this->refresh_token_response)) {
            throw new OauthInvalidToken('Refresh token must be exchanged for an access token before use, ensure to call refreshToken method first');
        }
        return $this->refresh_token_response;
    }
}
