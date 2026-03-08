# PHP Functions

[![GitHub Actions][GA Image]][GA Link]
[![Code Coverage][Coverage Image]][CodeCov Link]
[![Downloads][Downloads Image]][Packagist Link]
[![Packagist][Packagist Image]][Packagist Link]

## Functions

### absurd()

Function that should have never been called. 
Useful for `default` case in exhaustive matching.

### assert_return()

Asserts the value via the expression and returns it.

It's useful when you want to assert inline so for example you can keep the arrow function in place.

It uses native `assert()` internally.

```php
use function Cdn77\Functions\assert_return;

array_map(
    fn (mixed $value) => new RequiresInt(assert_return($value, is_int(...))),
    [1, 2, 3]
);
```

### noop()

Does nothing. Useful e.g. for `match` expression that currently supports single-line expressions in blocks.

```php
match ($val) {
  '1' => throw new Exception,
  '2' => foo(),
  default => noop(),
};
```

## Ds

### `mapFromEntries()`

Creates a map from an iterable of entries.

```php
use function Cdn77\Functions\mapFromEntries;

$map = mapFromEntries([
  ['foo', 'bar'],
  ['baz', 'qux'],
]);

assert($map->get('foo') === 'bar');
```

### `mappedMapsFromIterable()`

Groups an iterable into a `Map<K, Map<KInner, V>>` using a mapper that returns nested `Pair`s.

```php
use Ds\Pair;
use function Cdn77\Functions\mappedMapsFromIterable;

$map = mappedMapsFromIterable(
    ['a' => 1, 'b' => 2, 'c' => 1],
    static fn (string $key, int $value) => new Pair($value, new Pair($key, $key . '_')),
);

assert($map->get(1)->get('a') === 'a_');
assert($map->get(1)->get('c') === 'c_');
assert($map->get(2)->get('b') === 'b_');
```

## Iterable

### find()

Finds a value in an iterable.

```php
use function Cdn77\Functions\Iterable\find;

$iterable = [0, 1, 2, 3];
$option = find($iterable, static fn (mixed $_, int $value) => $value < 2);

assert($option->unwrap() === 0);
```

[GA Image]: https://github.com/cdn77/PhpFunctions/workflows/CI/badge.svg

[GA Link]: https://github.com/cdn77/PhpFunctions/actions?query=workflow%3A%22CI%22+branch%3Amaster

[Coverage Image]: https://codecov.io/gh/cdn77/PhpFunctions/branch/0.2.x/graph/badge.svg

[CodeCov Link]: https://codecov.io/gh/cdn77/PhpFunctions/branch/0.2.x

[Downloads Image]: https://poser.pugx.org/cdn77/functions/d/total.svg

[Packagist Image]: https://poser.pugx.org/cdn77/functions/v/stable.svg

[Packagist Link]: https://packagist.org/packages/cdn77/functions
