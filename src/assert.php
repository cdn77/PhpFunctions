<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Throwable;

use function assert;

/**
 * @param TValue $assertionFn
 * @param callable(TValue):(bool|string) $assertionFn
 *
 * @template TValue
 */
function assert_return(mixed $value, callable $assertionFn, Throwable|string|null $description = null): mixed
{
    assert($assertionFn($value), $description);

    return $value;
}
