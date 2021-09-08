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
```php
try {
    // create an instance of the SDK with the desired configuration
    $client = new \Webmarketer\WebmarketerSdk([
        'credential' => '{ ...jsonSa }',
        'scopes' => 'test',
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

### Official integrations
Following integrations are developed and maintained by the Webmarketer Team himself and are based on this SDK.
* [WordPress Plugin](https://github.com/webmarketer-saas/wp-webmarketer)

## Contributing
All SDK dependencies are managed via Composer :
```bash
composer install
```
Run all tests with PHPUnit and the configuration provided :
```bash
composer tests
```
Run all tests and check codecoverage (must be >= 80%) with PHPUnit :
```bash
composer tests-coverage
```
Lint code :
```bash
composer phpcs
```

### Docker
If you want to run the SDK in a container, it comes with some Docker configuration :
* Dockerfile (Linux Alpine with PHP 5.6 and composer)
* Dockerfile-8 (Linux Alpine with PHP 8.0.10 and composer)
* docker-compose.yml with a service **PHP** to run commands with desired image (5.6 or 8.0.10). Update the used Dockerfile between the two to run with 5.6 or 8.0.10  
  ```bash
  docker-compose run php composer tests
  ```
---
Feel free to report issues and bugs directly on this repository.

## Resources
* [App](https://app.webmarketer.io)
* [Official documentation](https://doc.webmarketer.io)
* [Official site](https://webmarketer.io)