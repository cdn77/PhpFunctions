<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Throwable;

use function assert;

/** @phpstan-pure */
function assert_return(mixed $value, callable $assertionFn, Throwable|string|null $description = null): mixed
{
    assert($assertionFn($value), $description);

    return $value;
}
