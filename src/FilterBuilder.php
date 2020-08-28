<?php

namespace App\Sieve;

use App\Sieve\Filters\EnumFilter;
use App\Sieve\Filters\StringFilter;

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
}
