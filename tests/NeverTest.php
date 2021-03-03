<?php

declare(strict_types=1);

namespace Cdn77\Functions\Tests;

use PHPUnit\Framework\TestCase;
use Throwable;

use function Cdn77\Functions\never;

final class NeverTest extends TestCase
{
    public function testReturnsNever() : void
    {
        $this->expectException(Throwable::class);

        /** @psalm-suppress UnusedFunctionCall */
        never();
    }
}
