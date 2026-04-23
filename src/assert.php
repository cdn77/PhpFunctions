<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Throwable;

use function assert;

/**
 * @param TValue $value
 * @param callable(TValue):(bool|string) $assertionFn
 *
 * @return TValue
 *
 * @template TValue
 */
function assert_return(mixed $value, callable $assertionFn, Throwable|string|null $description = null): mixed
{
    assert($assertionFn($value), $description);

    return $value;
}
