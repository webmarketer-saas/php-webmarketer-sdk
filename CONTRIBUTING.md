# Contributing
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