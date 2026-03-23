<?php

declare(strict_types=1);

namespace Src\Application\Auth\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    protected $message = 'Invalid credentials';

    protected $code = 422;
}
