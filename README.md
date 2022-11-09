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
