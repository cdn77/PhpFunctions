<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Ds\Map;
use Ds\Pair;
use Ds\Set;
use Ds\Vector;

/**
 * @see      Pair
 *
 * @param iterable<mixed, mixed> $iterable
 * @param callable(K, V): Pair<K2, V2> $mapper
 *
 * @return Map<K2, V2>
 *
 * @template K
 * @template V
 * @template K2
 * @template V2
 * @psalm-param iterable<K, V> $iterable
 */
function mapFromIterable(iterable $iterable, callable $mapper) : Map
{
    /** @var Map<K2, V2> $map */
    $map = new Map();

    foreach ($iterable as $key => $value) {
        $keyValue = $mapper($key, $value);
        $map->put($keyValue->key, $keyValue->value);
    }

    return $map;
}

/**
 * @param iterable<mixed, mixed> $iterable
 * @param callable(K,V): V2 $mapper
 *
 * @return Set<V2>
 *
 * @template K
 * @template V
 * @template V2
 * @psalm-param iterable<K, V> $iterable
 */
function setFromIterable(iterable $iterable, callable $mapper) : Set
{
    /** @var Set<V2> $set */
    $set = new Set();

    foreach ($iterable as $key => $value) {
        $set->add($mapper($key, $value));
    }

    return $set;
}

/**
 * @param callable(K, V): V2 $mapper
 *
 * @return Vector<V2>
 *
 * @template K
 * @template V
 * @template V2
 * @psalm-param iterable<K, V> $iterable
 */
function vectorFromIterable(iterable $iterable, callable $mapper) : Vector
{
    /** @var Vector<V2> $vector */
    $vector = new Vector();

    foreach ($iterable as $key => $value) {
        $vector->push($mapper($key, $value));
    }

    return $vector;
}
