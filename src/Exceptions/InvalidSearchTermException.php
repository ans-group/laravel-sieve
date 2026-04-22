<?php

declare(strict_types=1);

namespace UKFast\Sieve\Exceptions;

use RuntimeException;

class InvalidSearchTermException extends RuntimeException
{
    public ?array $allowedValues = [];
    public ?string $property = '';
}
