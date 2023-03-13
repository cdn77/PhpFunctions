<?php

declare(strict_types=1);

namespace Cdn77\Functions\Tests;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Cdn77\Functions\emptyGenerator;
use function Cdn77\Functions\iterableToGenerator;

#[CoversFunction('Cdn77\Functions\emptyGenerator')]
#[CoversFunction('Cdn77\Functions\iterableToGenerator')]
final class GeneratorTest extends TestCase
{
    public function testEmptyGenerator(): void
    {
        $buffer = [];
        foreach (emptyGenerator() as $item) {
            $buffer[] = $item;
        }

        self::assertSame([], $buffer);
    }

    public function testIterableToGenerator(): void
    {
        $buffer = [];
        foreach (iterableToGenerator([1, 2]) as $item) {
            $buffer[] = $item;
        }

        self::assertSame([1, 2], $buffer);
    }
}
