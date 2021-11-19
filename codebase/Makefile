explain:
	@echo "Run `make test` or `make test_coverage`"

test:
	php bin/phpunit

test_coverage:
	php bin/phpunit --coverage-html tests/Coverage

migrate:
	php bin/console doctrine:database:drop --force --if-exists
	php bin/console doctrine:database:create
	php bin/console doctrine:migrations:migrate --no-interaction
	php bin/console doctrine:fixtures:load --no-interaction
