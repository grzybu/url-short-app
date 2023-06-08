# Short url app

Simple service for generating short urls similar to Bitly based on Symfony 6.3.
It has simple frontend, but can be also used as Rest API to include it in different projects.

## Used technologies
- symfony
- doctrine orm
- nginx
- postgres
- phpunit
- php-cs, phpstan, psalm, phpmd
- docker
- docker-compose

## Local setup

### Requirements
- Docker
- Docker Compose
- Make (sudo apt-get install make)

### Initial setup
- Run `make init` to setup the project
- Open `http://localhost:8080/` in your browser

## URLS within the app

- [Application](http://localhost:8080/)

- [API Doc / Swagger](http://localhost:8080/api/doc)

## Tests
- `make test-show-coverage` - runs tests and shows coverage
