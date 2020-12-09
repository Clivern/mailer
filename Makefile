COMPOSER ?= composer
ARTISAN ?= ./artisan


help: Makefile
	@echo
	@echo " Choose a command run in Mailer:"
	@echo
	@sed -n 's/^##//p' $< | column -t -s ':' |  sed -e 's/^/ /'
	@echo


composer:
	$(COMPOSER) install


## fix: Fix code style issues
fix:
	./vendor/bin/php-cs-fixer fix


## fix-diff: Get code style issues
fix-diff:
	./vendor/bin/php-cs-fixer fix --diff --dry-run -v


## test: Run test cases
test: composer
	@echo "\n==> Run Test Cases:"
	cp .env.example .env
	$(ARTISAN) key:generate
	$(ARTISAN) test


## serve: Run the application
serve:
	@echo "\n==> Start the application:"
	cp .env.example .env
	$(ARTISAN) key:generate
	$(ARTISAN) serve


## lint: Lint PHP files
lint: lint-php phpcs php-cs lint-composer lint-eol
	@echo All good.


lint-eol:
	@echo "\n==> Validating unix style line endings of files:files"
	@! grep -lIUr --color '^M' app/ composer.json composer.lock || ( echo '[ERROR] Above files have CRLF line endings' && exit 1 )
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


## outdated: Get list of outdated packages
outdated:
	@echo "\n==> Show Outdated Packages:"
	$(COMPOSER) outdated


## ci: Run all sanity checks
ci: composer lint test outdated
	@echo "All quality checks passed"


.PHONY: test composer phpcs php-cs lint lint-php ci artisan