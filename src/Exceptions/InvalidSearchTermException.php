<?php

namespace UKFast\Sieve\Exceptions;

use RuntimeException;
use UKFast\Sieve\SearchTerm;

class InvalidSearchTermException extends RuntimeException
{
    public ?array $allowedValues = [];
}
