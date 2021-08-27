<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Ds\Map;
use Ds\Pair;
use Ds\Set;
use Ds\Vector;

/**
 * @param iterable<K, V> $iterable
 * @param callable(K, V): Pair<KReturn, VReturn> $mapper
 *
 * @return Map<KReturn, VReturn>
 *
 * @template K
 * @template V
 * @template KReturn
 * @template VReturn
 */
function mapFromIterable(iterable $iterable, callable $mapper) : Map
{
    /** @var Map<KReturn, VReturn> $map */
    $map = new Map();

    foreach ($iterable as $key => $value) {
        $keyValue = $mapper($key, $value);
        $map->put($keyValue->key, $keyValue->value);
    }

    return $map;
}

/**
 * @param iterable<K, V> $iterable
 * @param callable(K,V): VReturn $mapper
 *
 * @return Set<VReturn>
 *
 * @template K
 * @template V
 * @template VReturn
 */
function setFromIterable(iterable $iterable, callable $mapper) : Set
{
    /** @var Set<VReturn> $set */
    $set = new Set();

    foreach ($iterable as $key => $value) {
        $set->add($mapper($key, $value));
    }

    return $set;
}

/**
 * @param iterable<K, V> $iterable
 * @param callable(K, V): VReturn $mapper
 *
 * @return Vector<VReturn>
 *
 * @template K
 * @template V
 * @template VReturn
 */
function vectorFromIterable(iterable $iterable, callable $mapper) : Vector
{
    /** @var Vector<VReturn> $vector */
    $vector = new Vector();

    foreach ($iterable as $key => $value) {
        $vector->push($mapper($key, $value));
    }

    return $vector;
}
