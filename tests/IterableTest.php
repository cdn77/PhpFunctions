<?php

declare(strict_types=1);

namespace Cdn77\Functions\Tests;

use PhpOption\None;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Cdn77\Functions\Iterable\find;

#[CoversFunction('Cdn77\Functions\Iterable\find')]
final class IterableTest extends TestCase
{
    public function testFindFirst(): void
    {
        $iterable = [0, 1, 2, 3];
        $option = find($iterable, static fn (mixed $_, int $value) => $value < 2);

        self::assertSame(0, $option->getOrElse(null));
    }

    public function testDontFind(): void
    {
        $iterable = [0, 1, 2, 3];
        $option = find($iterable, static fn (mixed $_, int $value) => $value > 3);

        self::assertSame(None::create(), $option);
    }
}
