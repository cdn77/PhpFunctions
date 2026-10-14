<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Ds\Map;

/**
 * Stores the value when the key is absent or mapped to null.
 * Returns the previous value, or null when the key was absent or mapped to null.
 *
 * @param Map<K, V> $map
 * @param K $key
 * @param V $value
 *
 * @return V|null
 *
 * @template K
 * @template V
 */
function mapPutIfAbsent(Map $map, mixed $key, mixed $value): mixed
{
    $previousValue = $map->get($key, null);
    if ($previousValue === null) {
        $map->put($key, $value);
    }

    return $previousValue;
}

/**
 * Computes a value using the key and its current value, or null when absent.
 * A null computed value removes the key, including an existing null mapping.
 * Returns the computed value, or null when the key is removed or remains absent.
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
    $computedValue = $computer($key, $map->get($key, null));

    if ($computedValue === null) {
        $map->remove($key, null);
    } else {
        $map->put($key, $computedValue);
    }

    return $computedValue;
}

/**
 * Computes a value only when the key is absent or mapped to null.
 * A null computed value leaves the map unchanged, including an existing null mapping.
 * Returns the existing non-null value or the computed value, which can be null.
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
    $previousValue = $map->get($key, null);
    if ($previousValue !== null) {
        return $previousValue;
    }

    $computedValue = $computer($key);
    if ($computedValue !== null) {
        $map->put($key, $computedValue);
    }

    return $computedValue;
}

/**
 * Computes a value only when the key is mapped to a non-null value.
 * A null computed value removes the key; an existing null mapping is left unchanged.
 * Returns the computed value, or null when computation is skipped or the key is removed.
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
    $previousValue = $map->get($key, null);
    if ($previousValue === null) {
        return null;
    }

    $computedValue = $computer($key, $previousValue);
    if ($computedValue === null) {
        $map->remove($key);
    } else {
        $map->put($key, $computedValue);
    }

    return $computedValue;
}
