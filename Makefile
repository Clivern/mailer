COMPOSER ?= composer
ARTISAN ?= ./artisan


composer:
	$(COMPOSER) install


fix:
	./vendor/bin/php-cs-fixer fix


fix-diff:
	./vendor/bin/php-cs-fixer fix --diff --dry-run -v


test: composer
	@echo "\n==> Run Test Cases:"
	$(ARTISAN) test


lint: lint-php phpcs php-cs lint-composer lint-eol
	@echo All good.


lint-eol:
	@echo "\n==> Validating unix style line endings of files:files"
	@! grep -lIUr --color '^M' src/ composer.json composer.lock || ( echo '[ERROR] Above files have CRLF line endings' && exit 1 )
	@echo All files have valid line endings


lint-composer:
	@echo "\n==> Validating composer.json and composer.lock:"
	$(COMPOSER) validate --strict


lint-php:
	@echo "\n==> Validating all php files:"
	@find app tests -type f -name \*.php | while read file; do php -l "$$file" || exit 1; done


phpcs:
	vendor/bin/phpcs


php-cs:
	vendor/bin/php-cs-fixer fix --diff --dry-run -v


outdated:
	@echo "\n==> Show Outdated Packages:"
	$(COMPOSER) outdated


ci: composer lint test outdated
	@echo "All quality checks passed"


.PHONY: test composer phpcs php-cs lint lint-php ci