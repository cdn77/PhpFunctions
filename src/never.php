<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Exception;

/**
 * @psalm-pure
 * @psalm-return no-return
 */
function never() : void
{
    throw new Exception('This should never have happened');
}
