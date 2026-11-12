---
name: cdn77-functions
description: >-
  Use cdn77/functions PHP helpers for unreachable code, inline assertions,
  ext-ds v1 collection mapping, map mutation, and generators. Use when code imports
  Cdn77\Functions, calls absurd(), assert_return(), vectorFromIterable(),
  setFromIterable(), mapFromIterable(), mappedMapsFromIterable(), mapCompute(),
  or related helpers, or when choosing absurd() versus Psl\invariant_violation().
---

# CDN77 Functions

Check the installed version before choosing a helper. The `0.2.x` branch requires
PHP 8.4+ and ext-ds v1. Composer autoloads the functions; import them with
`use function Cdn77\Functions\name;`.

## Unreachable code and runtime failures

- Reserve `absurd()` for a path ruled out by a local invariant, such as an
  exception that cannot occur for a fixed, valid input. Explain the invariant
  when it is not clear from the code. Merely expecting success is not enough.
- `absurd()` returns `never` and always throws a generic `Exception` with a fixed
  message. It takes no arguments; do not pass a diagnostic message to it.
- For a broken runtime contract, unexpected external response, or invalid state,
  prefer `Psl\invariant()` or `Psl\invariant_violation()` with a specific message
  when PSL is already available. PSL is not a dependency of this library; do not
  add it solely for this guidance. Otherwise use the project's exception pattern.
- Handle expected input errors through validation or domain exceptions. Do not
  replace every `absurd()` mechanically or hide a recoverable error behind it.

For example, an external response can violate a contract at runtime:

```php
use function Psl\invariant_violation;

if (!$link instanceof Uplink) {
    invariant_violation('The API returned a non-Uplink link.');
}
```

## Inline assertions

- `assert_return($value, $assertionFn, $description)` asserts the predicate and
  returns the original value unchanged. It does not convert or sanitize it.
- It uses native `assert()`, which can be disabled. Use it only for internal
  assertions, not required runtime validation, authorization, or external input.
- Keep the assertion callback free of side effects; it might not execute.

## Collection construction

- All mapping callbacks receive **key first, value second**, unlike common
  value-first APIs. `mapComputeIfAbsent()` is different: it receives only the key.
- Prefer `vectorFromIterable()` and `setFromIterable()` when transforming values.
  Without a transformation, pass the iterable directly to `new Vector()` or
  `new Set()`.
- Use `mapFromEntries()` for iterable `[key, value]` pairs. Use `mapFromIterable()`
  when the callback produces a `Ds\Pair` containing the output key and value.
  Repeated output keys replace earlier values.
- Use `mappedVectorsFromIterable()`, `mappedQueuesFromIterable()`, or
  `mappedSetsFromIterable()` to group mapped values by the key in a returned
  `Ds\Pair`. Sets remove duplicate values; vectors and queues retain them.
- `mappedMapsFromIterable()` expects `Pair(groupKey, Pair(innerKey, value))`.
  Repeated inner keys replace values within that group.

```php
use function Cdn77\Functions\vectorFromIterable;

$lengths = vectorFromIterable(
    ['first' => 'ab', 'second' => 'cde'],
    static fn (string $_, string $value) => strlen($value),
);
```

## Map mutation

These helpers mutate the supplied `Ds\Map`; they do not return a new map.

- `mapPutIfAbsent()` stores the supplied value for a missing or null mapping and
  returns the previous value, not the newly stored value.
- `mapCompute()` calls the callback with the key and current value (null if
  absent). A null result removes the key; a non-null result replaces its value.
- `mapComputeIfAbsent()` calls the callback only for a missing or null mapping.
  A null result leaves the map unchanged, including an existing null mapping.
- `mapComputeIfPresent()` calls the callback only for a non-null mapping. A null
  result removes the key; an existing null mapping is left unchanged.
- Compute helpers return the computed value, or null when skipped or removed;
  `mapComputeIfAbsent()` returns the existing value when computation is skipped.
- Do not modify the same map from a compute callback.

## Generators and no-op expressions

- Use `emptyGenerator()` when an API requires a `Generator` with no values.
- Use `iterableToGenerator()` to delegate iteration with `yield from`, preserving
  keys without materializing an array. It does not make a one-shot iterable reusable.
- `noop()` returns void. Use it only where an explicit no-op is intentional, not
  to conceal an unhandled case or failed operation.

## Source of truth

Read the installed package's `src/absurd.php`, `src/assert.php`, `src/ds.php`,
`src/ds_map.php`, and `src/generator.php` for exact signatures and behavior.
In a consuming project these are under `vendor/cdn77/functions/`. Check that a
helper exists in that version before using it; do not infer APIs from its name.
