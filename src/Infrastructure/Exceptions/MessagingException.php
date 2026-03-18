<?php

declare(strict_types=1);

namespace Src\Infrastructure\Exceptions;

final class MessagingException extends \RuntimeException
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
