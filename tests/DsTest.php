<?php

declare(strict_types=1);

namespace Cdn77\Functions\Tests;

use Ds\Map;
use Ds\Pair;
use Generator;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Cdn77\Functions\mapCompute;
use function Cdn77\Functions\mapComputeIfAbsent;
use function Cdn77\Functions\mapComputeIfPresent;
use function Cdn77\Functions\mapFromEntries;
use function Cdn77\Functions\mapFromIterable;
use function Cdn77\Functions\mappedMapsFromIterable;
use function Cdn77\Functions\mappedQueuesFromIterable;
use function Cdn77\Functions\mappedSetsFromIterable;
use function Cdn77\Functions\mappedVectorsFromIterable;
use function Cdn77\Functions\mapPutIfAbsent;
use function Cdn77\Functions\setFromIterable;
use function Cdn77\Functions\vectorFromIterable;

#[CoversFunction('Cdn77\Functions\mapCompute')]
#[CoversFunction('Cdn77\Functions\mapComputeIfAbsent')]
#[CoversFunction('Cdn77\Functions\mapComputeIfPresent')]
#[CoversFunction('Cdn77\Functions\mapFromIterable')]
#[CoversFunction('Cdn77\Functions\mapPutIfAbsent')]
#[CoversFunction('Cdn77\Functions\mappedMapsFromIterable')]
#[CoversFunction('Cdn77\Functions\mappedQueuesFromIterable')]
#[CoversFunction('Cdn77\Functions\mappedSetsFromIterable')]
#[CoversFunction('Cdn77\Functions\mappedVectorsFromIterable')]
#[CoversFunction('Cdn77\Functions\setFromIterable')]
#[CoversFunction('Cdn77\Functions\vectorFromIterable')]
final class DsTest extends TestCase
{
    public function testMapCompute(): void
    {
        /** @var Map<int, int|null> $map */
        $map = new Map([1 => 0, 2 => null, 3 => null]);
        $insertValue = static function (int $key, int|null $value): int {
            self::assertNull($value);

            return $key;
        };
        $removeNull = static fn (int $key, int|null $value) => $value === null ? null : $key + $value;

        self::assertSame(
            1,
            mapCompute($map, 1, static function (int $key, int|null $value): int {
                self::assertNotNull($value);

                return $key + $value;
            }),
        );
        self::assertSame(1, $map->get(1));

        self::assertSame(0, mapCompute($map, 0, $insertValue));
        self::assertSame(0, $map->get(0));
        self::assertSame(2, mapCompute($map, 2, $insertValue));
        self::assertSame(2, $map->get(2));

        self::assertNull(mapCompute($map, 1, static fn () => null));
        self::assertFalse($map->hasKey(1));
        self::assertNull(mapCompute($map, 3, $removeNull));
        self::assertFalse($map->hasKey(3));
        self::assertNull(mapCompute($map, 4, $removeNull));
        self::assertFalse($map->hasKey(4));
    }

    public function testMapComputeIfAbsent(): void
    {
        /** @var Map<int, int|null> $map */
        $map = new Map([1 => 0, 2 => null, 3 => null]);

        self::assertSame(0, mapComputeIfAbsent($map, 1, static fn () => self::fail('Callback must not run.')));
        self::assertSame(0, $map->get(1));

        self::assertSame(0, mapComputeIfAbsent($map, 0, static fn (int $key) => $key));
        self::assertSame(0, $map->get(0));
        self::assertSame(2, mapComputeIfAbsent($map, 2, static fn (int $key) => $key));
        self::assertSame(2, $map->get(2));

        self::assertNull(mapComputeIfAbsent($map, 3, static fn () => null));
        self::assertNull($map->get(3));
        self::assertNull(mapComputeIfAbsent($map, 4, static fn () => null));
        self::assertFalse($map->hasKey(4));
    }

    public function testMapComputeIfPresent(): void
    {
        /** @var Map<int, int|null> $map */
        $map = new Map([1 => 1, 2 => null]);

        self::assertNull(mapComputeIfPresent($map, 3, static fn () => self::fail('Callback must not run.')));
        self::assertFalse($map->hasKey(3));
        self::assertNull(mapComputeIfPresent($map, 2, static fn () => self::fail('Callback must not run.')));
        self::assertNull($map->get(2));

        self::assertSame(
            0,
            mapComputeIfPresent($map, 1, static function (int $key, int|null $value): int {
                self::assertNotNull($value);

                return $value - $key;
            }),
        );
        self::assertSame(0, $map->get(1));
        self::assertNull(mapComputeIfPresent($map, 1, static fn () => null));
        self::assertFalse($map->hasKey(1));
    }

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

    public function testMapPutIfAbsent(): void
    {
        /** @var Map<int, int|null> $map */
        $map = new Map([1 => 0, 2 => null]);

        self::assertSame(0, mapPutIfAbsent($map, 1, 3));
        self::assertSame(0, $map->get(1));
        self::assertNull(mapPutIfAbsent($map, 0, 3));
        self::assertSame(3, $map->get(0));
        self::assertNull(mapPutIfAbsent($map, 2, 4));
        self::assertSame(4, $map->get(2));
        self::assertNull(mapPutIfAbsent($map, 3, null));
        self::assertNull($map->get(3));
    }

    public function testMappedMapsFromIterable(): void
    {
        $iterableFactory = static function (): Generator {
            yield 1 => 'a';
            yield 1 => 'b';
            yield 2 => 'c';
            yield 2 => 'd';
        };

        $map = mappedMapsFromIterable(
            $iterableFactory(),
            static fn (int $key, string $value) => new Pair($key * 2, new Pair($value, $value . '_')),
        );

        self::assertCount(2, $map);

        $innerAt2 = $map->get(2);
        self::assertCount(2, $innerAt2);
        self::assertSame('a_', $innerAt2->get('a'));
        self::assertSame('b_', $innerAt2->get('b'));

        $innerAt4 = $map->get(4);
        self::assertCount(2, $innerAt4);
        self::assertSame('c_', $innerAt4->get('c'));
        self::assertSame('d_', $innerAt4->get('d'));
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
