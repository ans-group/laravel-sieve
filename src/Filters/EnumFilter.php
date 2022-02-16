<?php

namespace UKFast\Sieve\Filters;

use UKFast\Sieve\Exceptions\InvalidSearchTermException;
use UKFast\Sieve\ModifiesQueries;
use UKFast\Sieve\SearchTerm;

class EnumFilter implements ModifiesQueries
{
    protected $allowedValues = [];

    public function __construct($allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    public function modifyQuery($query, SearchTerm $search)
    {
        if ($search->operator() == 'nin' || $search->operator() == 'in') {
            $terms = explode(",", $search->term());
            foreach ($terms as $term) {
                if (!in_array($term, $this->allowedValues())) {
                    throw new InvalidSearchTermException(
                        "{$search->property()} must be one of " . implode(", ", $this->allowedValues)
                    );
                }
            }
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

    public function allowedValues()
    {
        return $this->allowedValues;
    }
}