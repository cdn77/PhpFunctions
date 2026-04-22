<?php

declare(strict_types=1);

namespace Cdn77\Functions\Tests;

use Ds\Pair;
use Generator;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Cdn77\Functions\mapFromEntries;
use function Cdn77\Functions\mapFromIterable;
use function Cdn77\Functions\mappedQueuesFromIterable;
use function Cdn77\Functions\mappedSetsFromIterable;
use function Cdn77\Functions\mappedVectorsFromIterable;
use function Cdn77\Functions\setFromIterable;
use function Cdn77\Functions\vectorFromIterable;

#[CoversFunction('Cdn77\Functions\mapFromIterable')]
#[CoversFunction('Cdn77\Functions\mappedQueuesFromIterable')]
#[CoversFunction('Cdn77\Functions\mappedSetsFromIterable')]
#[CoversFunction('Cdn77\Functions\mappedVectorsFromIterable')]
#[CoversFunction('Cdn77\Functions\setFromIterable')]
#[CoversFunction('Cdn77\Functions\vectorFromIterable')]
final class DsTest extends TestCase
{
    public function testMapFromEntries(): void
    {
        $iterableFactory = static function (): Generator {
            yield [1, true];
            yield [2, true];
            yield [2, false];
        };

        $map = mapFromEntries($iterableFactory());

        self::assertCount(2, $map);
        self::assertTrue($map->get(1));
        self::assertFalse($map->get(2));
    }

    public function testMapFromIterable(): void
    {
        $iterableFactory = static function (): Generator {
            yield 1 => true;
            yield 2 => false;
            yield 2 => true;
        };

        $map = mapFromIterable($iterableFactory(), static fn (int $key, bool $value) => new Pair($key * 2, ! $value));

        self::assertCount(2, $map);
        self::assertNull($map->get(1, null));
        self::assertFalse($map->get(4));
    }

    public function testMappedQueuesFromIterable(): void
    {
        $iterableFactory = static function (): Generator {
            yield 1 => 'a';
            yield 1 => 'b';
            yield 2 => 'c';
            yield 2 => 'd';
        };

        $map = mappedQueuesFromIterable(
            $iterableFactory(),
            static function (int $key, string $value): Pair {
                /** @phpstan-var non-falsy-string $mappedValue */
                $mappedValue = $value . '_';

                return new Pair($key * 2, $mappedValue);
            },
        );

        self::assertCount(2, $map);

        $queueAt2 = $map->get(2);
        self::assertSame('a_', $queueAt2->pop());
        self::assertSame('b_', $queueAt2->pop());

        $queueAt4 = $map->get(4);
        self::assertSame('c_', $queueAt4->pop());
        self::assertSame('d_', $queueAt4->pop());
    }

    public function testMappedSetsFromIterable(): void
    {
        $iterableFactory = static function (): Generator {
            yield 1 => 'a';
            yield 1 => 'b';
            yield 2 => 'a';
            yield 2 => 'b';
        };

        $map = mappedSetsFromIterable(
            $iterableFactory(),
            static function (int $key, string $value): Pair {
                /** @phpstan-var non-falsy-string $mappedValue */
                $mappedValue = $value . '_';

                return new Pair($key * 2, $mappedValue);
            },
        );

        self::assertCount(2, $map);
        self::assertTrue($map->get(2)->contains('a_', 'b_'));
        self::assertTrue($map->get(4)->contains('a_', 'b_'));
    }

    public function testMappedVectorsFromIterable(): void
    {
        $iterableFactory = static function (): Generator {
            yield 1 => 'a';
            yield 1 => 'b';
            yield 2 => 'c';
            yield 2 => 'd';
        };

        $map = mappedVectorsFromIterable(
            $iterableFactory(),
            static function (int $key, string $value): Pair {
                /** @phpstan-var non-falsy-string $mappedValue */
                $mappedValue = $value . '_';

                return new Pair($key * 2, $mappedValue);
            },
        );

        self::assertCount(2, $map);
        self::assertSame(['a_', 'b_'], $map->get(2)->toArray());
        self::assertSame(['c_', 'd_'], $map->get(4)->toArray());
    }

    public function testSetFromIterable(): void
    {
        $iterableFactory = static function (): Generator {
            yield 1 => true;
            yield 2 => false;
            yield 2 => true;
        };

        $set = setFromIterable($iterableFactory(), static fn (int $_, bool $value) => ! $value);

        self::assertCount(2, $set);
        self::assertTrue($set->contains(true, false));
    }

    public function testVectorFromIterable(): void
    {
        $iterableFactory = static function (): Generator {
            yield 1 => true;
            yield 2 => false;
            yield 2 => true;
        };

        $vector = vectorFromIterable($iterableFactory(), static fn (int $_, bool $value) => ! $value);

        self::assertCount(3, $vector);
        self::assertSame([false, true, false], $vector->toArray());
    }
}
