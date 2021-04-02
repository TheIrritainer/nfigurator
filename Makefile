
.PHONY: test clean coverage

vendor: composer.json
	composer install --dev --ignore-platform-reqs

build:
	mkdir $@

test: vendor build
	./vendor/bin/phpunit

coverage: vendor build
	phpunit --coverage-html build/html/coverage

clean:
	rm --recursive --force -- vendor
	rm --recursive --force -- build
