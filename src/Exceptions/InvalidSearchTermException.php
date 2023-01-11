<?php

namespace UKFast\Sieve\Exceptions;

use RuntimeException;

class InvalidSearchTermException extends RuntimeException
{
    public ?array $allowedValues = [];
    public ?string $property = '';
}
