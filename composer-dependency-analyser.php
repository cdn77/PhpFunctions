<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    ->ignoreErrorsOnPath(
        'tests/AssertTest.php',
        [ErrorType::UNKNOWN_FUNCTION],
    )
    ->ignoreErrorsOnPackageAndPaths(
        'phpstan/phpstan',
        ['phpstan'],
        [ErrorType::DEV_DEPENDENCY_IN_PROD],
    )
    ->ignoreErrorsOnPackageAndPaths(
        'nikic/php-parser',
        ['phpstan'],
        [ErrorType::SHADOW_DEPENDENCY],
    )
    ->addPathToScan(__DIR__ . '/src', isDev: false);
