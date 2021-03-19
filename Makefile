# vim: set tabstop=8 softtabstop=8 noexpandtab:
phpstan:
	docker run --rm -it -w=/app -v ${PWD}:/app oskarstark/phpstan-ga:0.12.56 analyse src/ --level=7

cs:
	docker run --rm -it -w /app -v ${PWD}:/app oskarstark/php-cs-fixer-ga:2.16.7

test:
	php vendor/bin/phpunit -v
