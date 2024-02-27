<?php

namespace Webmarketer\Auth;

use Webmarketer\Exception\CredentialException;
use Webmarketer\Exception\DependencyException;
use Webmarketer\Utils\Url;
use Webmarketer\WebmarketerSdk;

class ServiceAccountAuthProvider extends AbstractAuthProvider
{
    const ENV_VAR_NAME = 'WEBMARKETER_APPLICATION_CREDENTIALS';
    const ALG = 'RS256';
    const TYPE = 'JWT';
    const JWT_GRANT_TYPE = 'urn:ietf:params:oauth:grant-type:jwt-bearer';

    /** @var string | null */
    private $credential;

    /** @var array */
    private $parsed_credential;

    /** @var string */
    private $scopes;

    /**
     * Construct an instance of the service account based auth
     * Default SDK authentication method
     *
     * @param array{
     *     credential?: string,
     *     scopes: string
     * } $args
     * credential : JSON string credential
     *              optional, if not provided SDK try to get JSON file from path in WEBMARKETER_APPLICATION_CREDENTIALS env var
     * scopes : Scopes space separated
     */
    public function __construct($args)
    {
        $this->credential = key_exists('credential', $args) && is_string($args['credential'])
            ? $args['credential']
            : null;
        $this->scopes = $args['scopes'];
    }

    protected function internalInit()
    {
        if (!function_exists('openssl_sign') || !in_array('RSA-SHA256', openssl_get_md_methods(true))) {
            throw new DependencyException('Missing crypto function openssl_sign() or signing alg RSA-SHA256');
        }

        $this->parsed_credential = is_null($this->credential)
            ? $this->loadCredentialFromEnv()
            : json_decode($this->credential, true);

        if (is_null($this->parsed_credential)) {
            throw new CredentialException('Credential invalid, can not be null, ensure SA is provided or properly configured via env var');
        }

        // ensure all fields are present in json credential
        foreach (['privateKey', 'privateKeyId', 'clientId', 'serviceAccountEmail'] as $field) {
            if (!array_key_exists($field, $this->parsed_credential)) {
                throw new CredentialException("Credential invalid, $field field missing");
            }
        }
    }

    protected function negotiateAccessToken()
    {
        // construct JWT header
        $header = (object) [
            'alg' => self::ALG,
            'typ' => self::TYPE,
            'kid' => $this->parsed_credential['privateKeyId']
        ];

        // construct JWT payload
        $payload = (object) [
            'iss' => $this->parsed_credential['clientId'],
            'sub' => $this->parsed_credential['serviceAccountEmail'],
            'aud' => join('/', [WebmarketerSdk::getBaseOauthPath(), 'token']),
            'scope' => $this->scopes,
            // 5 minutes is enough to negotiate an access token with the oauth server
            'exp' => time() + 60 * 5
        ];

        $jwt = join('.', [
            Url::base64Encode(json_encode($header)),
            Url::base64Encode(json_encode($payload))
        ]);

        // construct and sign JWT signature
        openssl_sign($jwt, $signature, $this->parsed_credential['privateKey'], 'RSA-SHA256');
        $jwt .= '.' . Url::base64Encode($signature);

        $response = $this->http_service->sendRequest(
            'POST',
            'token',
            [
                'grant_type' => self::JWT_GRANT_TYPE,
                'client_id' => $this->parsed_credential['clientId'],
                'assertion' => $jwt
            ],
            [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            WebmarketerSdk::getBaseOauthPath()
        );

        return new AccessTokenResponse(
            $response->body->access_token,
            $response->body->token_type,
            $response->body->expires_in,
            $response->body->scope,
            null
        );
    }

    /**
     * Load the JSON key from path specified in env var
     *
     * @return array | null;
     * @codeCoverageIgnore
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
}
