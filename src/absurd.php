<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Exception;

/**
 * @phpstan-return never
 *
 * @phpstan-pure
 */
function absurd(): void
{
    throw new Exception('Called `absurd` function which should be uncallable');
}
