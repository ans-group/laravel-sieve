<?php

namespace UKFast\Sieve;

use UKFast\Sieve\Filters\BooleanFilter;
use UKFast\Sieve\Filters\DateFilter;
use UKFast\Sieve\Filters\EnumFilter;
use UKFast\Sieve\Filters\NumericFilter;
use UKFast\Sieve\Filters\StringFilter;

class FilterBuilder
{
    protected $column;

    public function enum($cols)
    {
        return $this->wrapFilter(new EnumFilter($cols));
    }

    public function string()
    {
        return $this->wrapFilter(new StringFilter);
    }

    public function numeric()
    {
        return $this->wrapFilter(new NumericFilter);
    }

    public function date()
    {
        return $this->wrapFilter(new DateFilter);
    }

    public function boolean($trueVal = 1, $falseVal = 0)
    {
        return $this->wrapFilter(new BooleanFilter($trueVal, $falseVal));
    }

    public function for($column)
    {
        $this->column = $column;
    }

    /**
     * Wrap the filters so we can add extra information such as
     * remapped columns
     */
    protected function wrapFilter($filter)
    {
        $wrapped = new WrappedFilter($filter);
        $wrapped->column = $filter;
        $this->column = '';
    }
}
