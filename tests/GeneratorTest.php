<?php

declare(strict_types=1);

namespace Cdn77\Functions\Tests;

use PHPUnit\Framework\TestCase;

use function Cdn77\Functions\emptyGenerator;
use function Cdn77\Functions\iterableToGenerator;

final class GeneratorTest extends TestCase
{
    public function testEmptyGenerator() : void
    {
        self::assertCount(0, emptyGenerator());
    }

    public function testIterableToGenerator() : void
    {
        self::assertCount(2, iterableToGenerator([1, 2]));
    }
}
