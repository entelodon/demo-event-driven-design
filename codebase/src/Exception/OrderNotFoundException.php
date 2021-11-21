<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Throwable;

class OrderNotFoundException extends \Exception
{
    const MESSAGE_TEMPLATE = 'Order with Id %s was not found';

    #[Pure]
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE_TEMPLATE, $message), $code, $previous);
    }
}
