<?php

declare(strict_types=1);

namespace Cdn77\Functions\Tests;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Cdn77\Functions\noop;

#[CoversFunction('Cdn77\Functions\noop')]
final class NoopTest extends TestCase
{
    public function testRun(): void
    {
        noop();

        self::assertTrue(true);
    }
}
