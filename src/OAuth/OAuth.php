<?php

namespace Webmarketer\OAuth;

use Exception;
use Webmarketer\Exception\CredentialException;
use Webmarketer\Exception\BadRequestException;
use Webmarketer\Exception\EndpointNotFoundException;
use Webmarketer\Exception\GenericHttpException;
use Webmarketer\Exception\UnauthorizedException;
use Webmarketer\HttpService\HttpService;
use Webmarketer\WebmarketerSdk;

class OAuth
{
    const OAUTH_TOKEN_ENDPOINT = 'token';
    const ENV_VAR_NAME = 'WEBMARKETER_APPLICATION_CREDENTIALS';
    const CREDENTIAL_FIELDS = [
        'privateKey',
        'privateKeyId',
        'clientId',
        'serviceAccountEmail'
    ];
    const ALG = 'RS256';
    const TYPE = 'JWT';
    const JWT_GRANT_TYPE = 'urn:ietf:params:oauth:grant-type:jwt-bearer';

    /**
     * Array representation of SA JSON credential
     *
     * @var array | null
     */
    private $credential;

    /**
     * OAuth scopes, space separated
     *
     * @var string
     */
    private $scopes;

    /**
     * @var HttpService
     */
    private $http_service;

    /**
     * Construct a new instance of oauth dedicated to authenticate to Webmarketer
     * - using the credential string provided
     * - or looking for the credential file at location specified in WEBMARKETER_APPLICATION_CREDENTIALS env var
     *
     * @param string | null $credential
     * @param string $scopes
     *
     * @throws CredentialException
     */
    public function __construct($http_service, $credential, $scopes)
    {
        $this->http_service = $http_service;
        $this->credential = is_null($credential) ?
            $this->loadCredentialFromEnv() :
            json_decode($credential, true);
        $this->scopes = $scopes;

        $this->ensureCredentialValid();
    }

    /**
     * @return JWT
     *
     * @throws Exception
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws EndpointNotFoundException
     * @throws GenericHttpException
     */
    public function fetchAccessTokenWithCredential()
    {
        // construct JWT header
        $header = (object) [
            'alg' => self::ALG,
            'typ' => self::TYPE,
            'kid' => $this->credential['privateKeyId']
        ];

        // construct JWT payload
        $payload = (object) [
            'iss' => $this->credential['clientId'],
            'sub' => $this->credential['serviceAccountEmail'],
            'aud' => join('/', [WebmarketerSdk::BASE_OAUTH_PATH, self::OAUTH_TOKEN_ENDPOINT]),
            'scope' => $this->scopes,
            // 5 minutes is enough to negotiate an access token with the oauth server
            'exp' => time() + 60 * 5
        ];

        $jwt = join('.', [
            $this->base64UrlEncode(json_encode($header)),
            $this->base64UrlEncode(json_encode($payload))
        ]);

        // construct and sign JWT signature
        openssl_sign($jwt, $signature, $this->credential['privateKey'], 'RSA-SHA256');
        $jwt .= '.' . $this->base64UrlEncode($signature);

        $response = $this->http_service->sendRequest(
            'POST',
            'token',
            [
                'grant_type' => self::JWT_GRANT_TYPE,
                'client_id' => $this->credential['clientId'],
                'assertion' => $jwt
            ],
            [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            WebmarketerSdk::BASE_OAUTH_PATH,
            ['req-oauth-processor'] // Bypass OAuthProcessors
        );

        return new JWT($response->body->access_token);
    }

    /**
     * Load the JSON key from path specified in env var
     *
     * @return array | null;
     */
    private function loadCredentialFromEnv()
    {
        $path = getenv(self::ENV_VAR_NAME);
        if (empty($path) || !file_exists($path)) {
            return null;
        }

        $json_key = file_get_contents($path);
        return json_decode($json_key, true);
    }

    /**
     * Check if credential is present and well formatted
     *
     * @throws CredentialException
     */
    private function ensureCredentialValid()
    {
        if (is_null($this->credential)) {
            throw new CredentialException('Credential invalid, can not be null');
        }

        // ensure all fields are present in json credential
        foreach (self::CREDENTIAL_FIELDS as $field) {
            if (!array_key_exists($field, $this->credential)) {
                throw new CredentialException("Credential invalid, $field field missing");
            }
        }
    }

    /**
     * @param $str
     *
     * @return string
     */
    private function base64UrlEncode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}
