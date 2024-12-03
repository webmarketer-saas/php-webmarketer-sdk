<p align="center">
  <a href="https://webmarketer.io" target="_blank" align="center">
    <img src="https://avatars.githubusercontent.com/u/89253090?s=200&v=4" width="80">
  </a>
  <br />
</p>

# PHP SDK for Webmarketer

[![Latest Stable Version](http://poser.pugx.org/webmarketer/webmarketer-php/v)](https://packagist.org/packages/webmarketer/webmarketer-php)
[![Total Downloads](http://poser.pugx.org/webmarketer/webmarketer-php/downloads)](https://packagist.org/packages/webmarketer/webmarketer-php)
[![Latest Unstable Version](http://poser.pugx.org/webmarketer/webmarketer-php/v/unstable)](https://packagist.org/packages/webmarketer/webmarketer-php)
[![PHP Version Require](http://poser.pugx.org/webmarketer/webmarketer-php/require/php)](https://packagist.org/packages/webmarketer/webmarketer-php)
[![License](http://poser.pugx.org/webmarketer/webmarketer-php/license)](https://packagist.org/packages/webmarketer/webmarketer-php)

The official PHP SDK for Webmarketer (app.webmarketer.io).

## Install
To add this package, your project must meet several requirements :
* PHP >= 5.6
* Composer ([install composer](https://getcomposer.org))
* OpenSSL extension for PHP

This package is the **core** SDK and is **not** installed with any specific HTTP library. It uses **PSR-7 implementation** [Httplug](https://github.com/php-http/httplug) to be used with any PSR-7 compliant client.
If you don't know which to use, we recommend using [Guzzle](https://github.com/guzzle/guzzle) (you just need to require it in your project).

```bash
composer require webmarketer/webmarketer-php
```

## Usage
### Basic example
```php
try {
    // create an instance of the SDK with the desired configuration
    $client = new \Webmarketer\WebmarketerSdk([
        'default_project_id' => 'webmarketer-awesome-project'
    ]);
} catch (\Webmarketer\Exception\DependencyException $dep_ex) {
    // SDK init throw a dependency exception if requirements are not meet (see Install)
} catch (\Webmarketer\Exception\CredentialException $cred_ex) {
    // SDK automatically try to authenticate you agains API
    // A credential exception could be throw if credentials are invalid
}

// SDK exposes resources services, use them to manipulate your resources
$event_type_service = $client->getEventTypeService();
$field_service = $client->getFieldService();
```

### Authentication
The SDK exposes auth providers to let you chose how you want to authenticate against the Webmarketer API
#### Service Account Provider
This is the default authentication method used by the SDK if no provider is specified manually.  
Without **any configuration** the SDK will try to retrieve a service-account file in the location specified by the environment variable named `WEBMARKETER_APPLICATION_CREDENTIALS`.

**Example :**
- My Webmarketer Service Account is located at the following path on my server : `/secrets/webmarketer/sa.json`
- I set the env. variable on my server with the path : `WEBMARKETER_APPLICATION_CREDENTIALS=/secrets/webmarketer/sa.json`
- I do not need to specify an auth provider as the SDK will automatically authenticate with my Service Account

It is possible to specify the stringified JSON sa aswell by simply passing the `ServiceAccountAuthProvider` manually to the SDK :
```php
try {
    $sa_auth_provider = new \Webmarketer\Auth\ServiceAccountAuthProvider([
       // stringified service account
       'credential' => '{ ...serviceAccount }',
       // desired scopes, space separated
       'scopes' => 'full_access'
    ]);
    // create an instance of the SDK with the custom auth provider
    $client = new \Webmarketer\WebmarketerSdk(
        [
            'default_project_id' => 'webmarketer-awesome-project'
        ],
        $sa_auth_provider
    );
} catch (\Webmarketer\Exception\DependencyException $dep_ex) {
    // SDK init throw a dependency exception if requirements are not meet (see Install)
} catch (\Webmarketer\Exception\CredentialException $cred_ex) {
    // SDK automatically try to authenticate you agains API
    // A credential exception could be throw if credentials are invalid
}
```

#### Refresh Token Provider
The refresh token auth provider lets you authenticate against the API with a personal refresh token. You just need to instantiate the Webmarketer SDK with the `RefreshTokenAuthProvider` :
```php
try {
    $refresh_token_auth_provider = new \Webmarketer\Auth\RefreshTokenAuthProvider([
       // oauth application client_id
       'client_id' => 'appclientid',
       // oauth application client_secret
       'client_secret' => 'appclientsecret',
       // refresh token
       'refresh_token' => 'myrefreshtoken'
    ]);
    // create an instance of the SDK with the custom auth provider (this time the refresh_token_auth_provider)
    $client = new \Webmarketer\WebmarketerSdk(
        [
            'default_project_id' => 'webmarketer-awesome-project'
        ],
        $refresh_token_auth_provider
    );
    // perform an exchange of the refresh flow to get an access token as well as a new refresh token
    $response = $client->getAuthProvider()->refreshToken();
    // new refresh token can be securely stored for future use
    $new_refresh_token = $response['refresh_token'];
} catch (\Webmarketer\Exception\DependencyException $dep_ex) {
    // SDK init throw a dependency exception if requirements are not meet (see Install)
} catch (\Webmarketer\Exception\CredentialException $cred_ex) {
    // SDK automatically try to authenticate you agains API
    // A credential exception could be throw if credentials are invalid
}
```

### Official integrations
Following integrations are developed and maintained by the Webmarketer Team and are based on this SDK.
* [WordPress Plugin](https://github.com/webmarketer-saas/wp-webmarketer)
* [Prestashop Module](https://github.com/webmarketer-saas/prestashop-webmarketer)

## Resources
* [App](https://app.webmarketer.io)
* [Official documentation](https://doc.webmarketer.io)
* [Official site](https://webmarketer.io)