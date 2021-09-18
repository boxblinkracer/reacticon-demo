#
# Makefile
#

.PHONY: help
.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# ------------------------------------------------------------------------------------------------------------

prod: ## Installs all production dependencies
	composer install --no-dev
	cd src/Resources/app/storefront && npm install --production

dev: ## Installs all dev dependencies
	composer install
	cd src/Resources/app/storefront && npm install

clean: ## Cleans all dependencies
	rm -rf vendor
	rm -rf src/Resources/app/storefront/node_modules

build: ## Build
	cd /var/www/html && php bin/console theme:dump
	cd /var/www/html && ./bin/build-storefront.sh

# ------------------------------------------------------------------------------------------------------------

phpcheck: ## Starts the PHP syntax checks
	@find . -name '*.php' -not -path "./vendor/*" -not -path "./tests/*" | xargs -n 1 -P4 php -l

phpmin: ## Starts the PHP compatibility checks
	@php vendor/bin/phpcs -p --standard=PHPCompatibility --extensions=php --runtime-set testVersion 7.4 ./src

csfix: ## Starts the PHP CS Fixer
	@php vendor/bin/php-cs-fixer fix --config=./.php_cs.php --dry-run

stan: ## Starts the PHPStan Analyser
	php ./vendor/bin/phpstan --memory-limit=1G analyse .

phpunit: ## Starts all PHPUnit Tests
	php ./vendor/bin/phpunit --configuration=./phpunit.xml

jest: ## Starts all Jest tests
	cd ./src/Resources/app/storefront && ./node_modules/.bin/jest --config=.jest.config.js
