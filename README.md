# PHP Functions

[![GitHub Actions][GA Image]][GA Link]
[![Shepherd Type][Shepherd Image]][Shepherd Link]
[![Code Coverage][Coverage Image]][CodeCov Link]
[![Downloads][Downloads Image]][Packagist Link]
[![Packagist][Packagist Image]][Packagist Link]

## Functions

### absurd()

Function that should have never been called. 
Useful for `default` case in exhaustive matching.

### noop()

Does nothing. Useful e.g. for `match` expression that currently supports single-line expressions in blocks.

```php
match ($val) {
  '1' => throw new Exception,
  '2' => foo(),
  default => noop(),
};
```

## Iterable

### find()

Finds a value in an iterable.

```php
use function Cdn77\Functions\Iterable\find;

$iterable = [0, 1, 2, 3];
$option = find($iterable, static fn (mixed $_, int $value) => $value < 2);

assert($option->get() === 0);
```

[GA Image]: https://github.com/cdn77/PhpFunctions/workflows/CI/badge.svg

[GA Link]: https://github.com/cdn77/PhpFunctions/actions?query=workflow%3A%22CI%22+branch%3Amaster

[Shepherd Image]: https://shepherd.dev/github/cdn77/PhpFunctions/coverage.svg

[Shepherd Link]: https://shepherd.dev/github/cdn77/PhpFunctions

[Coverage Image]: https://codecov.io/gh/cdn77/PhpFunctions/branch/0.2.x/graph/badge.svg

[CodeCov Link]: https://codecov.io/gh/cdn77/PhpFunctions/branch/0.2.x

[Downloads Image]: https://poser.pugx.org/cdn77/functions/d/total.svg

[Packagist Image]: https://poser.pugx.org/cdn77/functions/v/stable.svg

[Packagist Link]: https://packagist.org/packages/cdn77/functions
