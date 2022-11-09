# PHP Functions

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
