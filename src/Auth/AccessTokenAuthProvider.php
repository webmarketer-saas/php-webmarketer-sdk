<?php

namespace Webmarketer\Auth;

use Webmarketer\Exception\CredentialException;

class AccessTokenAuthProvider extends AbstractAuthProvider
{
    const NEED_USER_INTERACTION = true;

    /** @var string */
    private $access_token;

    public function __construct($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * @inheritDoc
     */
    protected function internalInit()
    {
        if (!$this->access_token) {
            throw new CredentialException('Credential invalid, access token malformed');
        }
    }

    /**
     * @inheritDoc
     */
    protected function negotiateAccessToken()
    {
        return $this->access_token;
    }
}