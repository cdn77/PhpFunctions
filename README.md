# PHP Functions

## Contents

- [Functions](#functions)
  - [noop()](#noop)

## Functions

### noop()

Does nothing. Useful e.g. for `match` expression that currently supports single-line expressions in blocks.

```php
match ($val) {
  '1' => throw new Exception,
  '2' => foo(),
  default => noop(),
};
```
