<?php

declare(strict_types=1);

namespace Cdn77\Functions\Iterable;

use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;

/**
 * @param iterable<K, T> $iterable
 * @param callable(K, T): bool $filterFn
 *
 * @return Option<T>
 *
 * @template K
 * @template T
 */
function find(iterable $iterable, callable $filterFn): Option
{
    foreach ($iterable as $k => $v) {
        if ($filterFn($k, $v)) {
            return new Some($v);
        }
    }

    return None::create();
}
