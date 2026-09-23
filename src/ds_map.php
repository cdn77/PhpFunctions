<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Ds\Map;

/**
 * @param Map<K, V> $map
 * @param K $key
 * @param V $value
 *
 * @return V
 *
 * @template K
 * @template V
 */
function mapPutIfAbsent(Map $map, mixed $key, mixed $value): mixed
{
    if ($map->hasKey($key)) {
        return $map->get($key);
    }

    $map->put($key, $value);

    return $value;
}

/**
 * A null computed value removes the key, matching Java Map compute semantics.
 *
 * @param Map<K, V> $map
 * @param K $key
 * @param callable(K, V|null): (V|null) $computer
 *
 * @return V|null
 *
 * @template K
 * @template V
 */
function mapCompute(Map $map, mixed $key, callable $computer): mixed
{
    $hasKey = $map->hasKey($key);
    $computedValue = $computer($key, $hasKey ? $map->get($key) : null);

    if ($computedValue === null) {
        if ($hasKey) {
            $map->remove($key);
        }

        return null;
    }

    $map->put($key, $computedValue);

    return $computedValue;
}

/**
 * A null computed value leaves the key absent, matching Java Map computeIfAbsent semantics.
 *
 * @param Map<K, V> $map
 * @param K $key
 * @param callable(K): (V|null) $computer
 *
 * @return V|null
 *
 * @template K
 * @template V
 */
function mapComputeIfAbsent(Map $map, mixed $key, callable $computer): mixed
{
    if ($map->hasKey($key)) {
        return $map->get($key);
    }

    $computedValue = $computer($key);
    if ($computedValue === null) {
        return null;
    }

    $map->put($key, $computedValue);

    return $computedValue;
}

/**
 * A null computed value removes the key, matching Java Map computeIfPresent semantics.
 *
 * @param Map<K, V> $map
 * @param K $key
 * @param callable(K, V): (V|null) $computer
 *
 * @return V|null
 *
 * @template K
 * @template V
 */
function mapComputeIfPresent(Map $map, mixed $key, callable $computer): mixed
{
    if (! $map->hasKey($key)) {
        return null;
    }

    $computedValue = $computer($key, $map->get($key));
    if ($computedValue === null) {
        $map->remove($key);

        return null;
    }

    $map->put($key, $computedValue);

    return $computedValue;
}
