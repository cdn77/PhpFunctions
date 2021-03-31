$(shell test "`find . -name Makefile.include ! -mtime +1 | wc -l`" -eq 0 && curl -sSfLO https://ci-cd.pages.cdn77.dev/templates/php/Makefile.include)

include Makefile.include

.PHONY: infection
infection: vendor
	vendor/bin/roave-infection-static-analysis-plugin --min-msi=0 --min-covered-msi=0 --log-verbosity=none -s
