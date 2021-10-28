<?php

namespace UKFast\Sieve;

use UKFast\Sieve\Filters\BooleanFilter;
use UKFast\Sieve\Filters\DateFilter;
use UKFast\Sieve\Filters\EnumFilter;
use UKFast\Sieve\Filters\NumericFilter;
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

    public function numeric()
    {
        return new NumericFilter;
    }

    public function date()
    {
        return new DateFilter;
    }

    public function boolean($trueVal = 1, $falseVal = 0)
    {
        return new BooleanFilter($trueVal, $falseVal);
    }
}
