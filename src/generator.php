<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Generator;

/** @return Generator<mixed> */
function emptyGenerator(): Generator
{
    yield from [];
}

/**
 * @param iterable<K, V> $iterable
 *
 * @return Generator<K, V>
 *
 * @template K
 * @template V
 */
function iterableToGenerator(iterable $iterable): Generator
{
    yield from $iterable;
}
