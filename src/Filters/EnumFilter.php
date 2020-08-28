<?php

namespace App\Sieve\Filters;

use App\Sieve\Exceptions\InvalidSearchTermException;
use App\Sieve\ModifiesQueries;
use App\Sieve\SearchTerm;
use Illuminate\Database\Query\Builder;

class EnumFilter implements ModifiesQueries
{
    protected $allowedValues = [];

    public function __construct($allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    public function modifyQuery(Builder $query, SearchTerm $search)
    {
        if (!in_array($search->term(), $this->allowedValues)) {
            throw new InvalidSearchTermException(
                "{$search->property()} must be one of " . implode(", ", $this->allowedValues)
            );
        }

        if ($search->operator() == 'eq') {
            $query->where($search->column(), $search->term());
        }

        if ($search->operator() == 'neq') {
            $query->where($search->column(), '!=', $search->term());
        }

        if ($search->operator() == 'in') {
            $query->whereIn($search->column(), explode(',', $search->term()));
        }

        if ($search->operator() == 'nin') {
            $query->whereNotIn($search->column(), explode(',', $search->term()));
        }
    }

    public function operators()
    {
        return ['in', 'eq', 'neq', 'nin'];
    }
}