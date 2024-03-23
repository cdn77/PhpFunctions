<?php

declare(strict_types=1);

namespace Cdn77\Functions;

use Exception;

/** @phpstan-pure */
function absurd(): never
{
    throw new Exception('Called `absurd` function which should be uncallable');
}
