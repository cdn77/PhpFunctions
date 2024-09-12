<?php

declare(strict_types=1);

namespace Cdn77\Functions\Iterable;

use Psl\Option\Option;

/**
 * @deprecated Use {@see \Psl\Iter\search_opt} instead
 *
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
            return Option::some($v);
        }
    }

    return Option::none();
}
