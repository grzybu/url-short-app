PHP_CONTAINER=$(shell docker ps --format "{{.Names}}" | grep php)
.PHONY: help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

build:
	docker-compose down --rmi all --remove-orphans -v
	docker-compose build --pull --no-cache
	docker-compose up -d
	${MAKE} composer-install
	timeout 10 ${MAKE} run-migrations
start: ## Build and start containers
	docker-compose up -d
stop: ## Stop containers
	docker-compose down
test: ## Run tests
	docker exec ${PHP_CONTAINER} composer test
test-coverage: ## Run test and generate HTML coverage report
	docker exec ${PHP_CONTAINER} composer test-coverage
test-show-coverage: ## Run test and show coverage
	docker exec ${PHP_CONTAINER} composer test-show-coverage
check: ##run tests and code quality tools
	docker exec ${PHP_CONTAINER} composer check
run-migrations: ##run migrations
	docker exec ${PHP_CONTAINER} bin/console doctrine:migration:migrate --no-interaction
composer-install: #composer install
	docker exec ${PHP_CONTAINER} composer install
composer-install: #composer update
	docker exec ${PHP_CONTAINER} composer update



