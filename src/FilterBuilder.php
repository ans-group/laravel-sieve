<?php

namespace UKFast\Sieve;

use UKFast\Sieve\Filters\EnumFilter;
use UKFast\Sieve\Filters\StringFilter;

class FilterBuilder
{
    public function enum($cols)
    {
        return new EnumFilter($cols);
    }

    public function string()
    {
        return new StringFilter;
    }

    public function integer()
    {
        // Works for now
        return new StringFilter;
    }
}
