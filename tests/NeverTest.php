<?php

declare(strict_types=1);

namespace Cdn77\Functions\Tests;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;
use Throwable;

use function Cdn77\Functions\absurd;

#[CoversFunction('Cdn77\Functions\absurd')]
final class NeverTest extends TestCase
{
    public function testReturnsNever(): void
    {
        $this->expectException(Throwable::class);

        absurd();
    }
}
