SHELL := /bin/bash
include .container.env.local
.PHONY: *

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

init: build start composer-install run-migrations

build: ## Build containers
	docker-compose --env-file=.container.env.local down --rmi all --remove-orphans -v
	docker-compose --env-file=.container.env.local build --pull --no-cache
start: ## start containers
	docker-compose --env-file=.container.env.local up -d
	${MAKE} setup-test-db
stop: ## Stop containers
	docker-compose --env-file=.container.env.local down
test: ## Run tests
	docker exec ${DOCKER_PHP_CONTAINER_NAME} composer test
test-coverage: ## Run test and generate HTML coverage report
	docker exec ${DOCKER_PHP_CONTAINER_NAME} composer test-coverage
test-show-coverage: ## Run test and show coverage
	docker exec ${DOCKER_PHP_CONTAINER_NAME} composer test-show-coverage
check: ## run tests and code quality tools
	docker exec ${DOCKER_PHP_CONTAINER_NAME} composer check
run-migrations: ## run migrations
	docker exec ${DOCKER_PHP_CONTAINER_NAME} bin/console doctrine:migration:migrate --no-interaction
composer-install: ## composer install
	docker exec ${DOCKER_PHP_CONTAINER_NAME} composer install
composer-update: ## composer update
	docker exec --env-file .container.env.local ${DOCKER_PHP_CONTAINER_NAME} composer update
setup-test-db: ## setup test db
	docker exec ${DOCKER_PHP_CONTAINER_NAME} php bin/console --env=test doctrine:schema:create
exec:
	docker exec -it ${DOCKER_PHP_CONTAINER_NAME} bash


