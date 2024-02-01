bash:
	docker-compose exec air-quality-measurements-panel-app bash

up:
	docker-compose up -d

build:
	docker-compose build

pull:
	docker-compose pull

down:
	docker-compose down -v

sleep5:
	sleep 5

run: pull build up composer-install cache-clear create-database migrate load-fixtures

cache-clear:
	docker-compose exec air-quality-measurements-panel-app php bin/console cache:clear

composer-install:
	docker-compose exec air-quality-measurements-panel-app composer install

create-database:
	docker-compose exec air-quality-measurements-panel-app php bin/console doctrine:database:create --if-not-exists

create-test-database:
	docker-compose exec air-quality-measurements-panel-app php bin/console doctrine:database:create --if-not-exists --env=test

migrate:
	docker-compose exec air-quality-measurements-panel-app php bin/console doctrine:migrations:migrate --no-interaction

load-fixtures:
	docker-compose exec air-quality-measurements-panel-app php bin/console doctrine:fixtures:load --no-interaction

migrate-test-database:
	docker-compose exec air-quality-measurements-panel-app php bin/console doctrine:migrations:migrate --no-interaction --env=test

unit-tests:
	docker-compose exec air-quality-measurements-panel-app ./bin/phpunit -c phpunit.xml --testdox --testsuite unit

integration-tests:
	docker-compose exec air-quality-measurements-panel-app ./bin/phpunit -c phpunit.xml --testdox --testsuite integration

acceptance-tests:
	docker-compose exec air-quality-measurements-panel-app ./bin/phpunit -c phpunit.xml --testdox --testsuite acceptance

csfixer-dry-fix:
	docker-compose exec air-quality-measurements-panel-app ./vendor/bin/php-cs-fixer fix src --dry-run

csfixer-fix:
	docker-compose exec air-quality-measurements-panel-app ./vendor/bin/php-cs-fixer fix src -vv

lint:
	docker-compose exec air-quality-measurements-panel-app php bin/console lint:yaml config --parse-tags

make tests: create-test-database migrate-test-database lint unit-tests integration-tests acceptance-tests