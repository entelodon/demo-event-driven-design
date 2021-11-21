<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Throwable;

class PromotionalCodeDoesNotExistException extends CreateOrderDtoValidationException
{
    const MESSAGE_TEMPLATE = 'Promotional Code with CODE %s does not exist.';

    #[Pure]
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE_TEMPLATE, $message), $code, $previous);
    }
}
