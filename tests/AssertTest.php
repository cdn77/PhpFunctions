<?php

declare(strict_types=1);

namespace Cdn77\Functions\Tests;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Cdn77\Functions\assert_return;
use function is_int;
use function PHPStan\Testing\assertType;

#[CoversFunction('Cdn77\Functions\assert_return')]
final class AssertTest extends TestCase
{
    public function testAssertReturn(): void
    {
        $value = 1;
        self::assertSame(
            $value,
            /** @phpstan-ignore function.alreadyNarrowedType */
            assert_return($value, is_int(...)),
        );
    }

    public function phpstanType(mixed $value): void
    {
        $return = assert_return($value, is_int(...));

        assertType('int', $value);
        assertType('int', $return);
    }
}
