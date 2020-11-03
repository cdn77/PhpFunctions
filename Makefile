ifdef GITLAB_CI
COMPOSER_ARGS += --no-progress --no-interaction
endif

.PHONY: build
build: vendor
	@echo "Build successfully done."

.PHONY: list
list:
	@$(MAKE) -pRrq -f $(lastword $(MAKEFILE_LIST)) : 2>/dev/null | awk -v RS= -F: '/^# File/,/^# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1}}' | sort | egrep -v -e '^[^[:alnum:]]' -e '^$@$$'

vendor: composer.lock
	composer install $(COMPOSER_ARGS)
	@touch vendor

.PHONY: cs
cs: vendor
	vendor/bin/phpcs

.PHONY: fix
fix:
	vendor/bin/phpcbf

.PHONY: static-analysis
static-analysis: phpstan # templates compatibility, @todo update when support for Psalm is added

.PHONY: phpstan
phpstan: vendor
	vendor/bin/phpstan analyse

.PHONY: psalm
psalm: vendor
	vendor/bin/psalm

.PHONY: infection
infection: vendor
	vendor/bin/roave-infection-static-analysis-plugin --min-msi=0 --min-covered-msi=0 --log-verbosity=none -s

.PHONY: test
test: test-unit

.PHONY: test-unit
test-unit:
	vendor/bin/phpunit $(PHPUNIT_ARGS)

.PHONY: check
check: phpstan psalm cs test

.PHONY: check-and-fix
check-and-fix: fix check

.PHONY: clean
clean: clean-vendor

.PHONY: clean-vendor
clean-vendor:
	rm -rf vendor
