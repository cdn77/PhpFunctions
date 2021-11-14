<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Exception;

/**
 * @psalm-return never
 *
 * @psalm-pure
 */
function never() : void
{
    throw new Exception('This should never have happened');
}
