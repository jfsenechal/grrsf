
fix: ##@DoctrineExtensions Fix with PHP CS Fixer
	vendor/bin/php-cs-fixer fix

lint: ##@DoctrineExtensions Lint with PHP CS Fixer (dry-run)
	vendor/bin/php-cs-fixer fix --dry-run --diff --diff-format udiff --show-progress=none --verbose

