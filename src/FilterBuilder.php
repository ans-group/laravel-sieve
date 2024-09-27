<?php

namespace UKFast\Sieve;

use UKFast\Sieve\Filters\BooleanFilter;
use UKFast\Sieve\Filters\DateFilter;
use UKFast\Sieve\Filters\EnumFilter;
use UKFast\Sieve\Filters\NumericFilter;
use UKFast\Sieve\Filters\StringFilter;

class FilterBuilder
{
    protected ?WrapsFilter $wrapper = null;

    protected ?WrapsFilter $lastWrapper = null;

    public function enum($cols)
    {
        return $this->wrapFilter(new EnumFilter($cols));
    }

    public function string()
    {
        return $this->wrapFilter(new StringFilter());
    }

    public function numeric()
    {
        return $this->wrapFilter(new NumericFilter());
    }

    public function date()
    {
        return $this->wrapFilter(new DateFilter());
    }

    public function boolean($trueVal = 1, $falseVal = 0)
    {
        return $this->wrapFilter(new BooleanFilter($trueVal, $falseVal));
    }

    public function custom($filter)
    {
        return $this->wrapFilter($filter);
    }

    public function for($column): static
    {
        return $this->wrap(new MapFilter($column));
    }

    public function wrap(WrapsFilter $newWrapper): static
    {
        if ($this->wrapper instanceof \UKFast\Sieve\WrapsFilter) {
            $newWrapper->wrap($this->wrapper);
            $this->wrapper = $newWrapper;
            return $this;
        }

        $this->lastWrapper = $newWrapper;
        $this->wrapper = $newWrapper;
        return $this;
    }

    /**
     * Wrap the filters so we can add extra information such as
     * remapped columns
     */
    protected function wrapFilter($filter)
    {
        if ($this->wrapper instanceof \UKFast\Sieve\WrapsFilter) {
            $this->lastWrapper->wrap($filter);
            $wrapped = $this->wrapper;

            $this->wrapper = null;
            $this->lastWrapper = null;
            return $wrapped;
        }

        return $filter;
    }
}
