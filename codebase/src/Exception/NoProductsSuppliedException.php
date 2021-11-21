<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Throwable;

class NoProductsSuppliedException extends \Exception
{
    const MESSAGE_TEMPLATE = 'No products were supplied';

    #[Pure]
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE_TEMPLATE, $code, $previous);
    }
}
