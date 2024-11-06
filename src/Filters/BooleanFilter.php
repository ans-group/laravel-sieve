<?php

namespace UKFast\Sieve\Filters;

use UKFast\Sieve\ModifiesQueries;
use UKFast\Sieve\SearchTerm;

class BooleanFilter implements ModifiesQueries
{
    public function __construct(protected $trueVal = 1, protected $falseVal = 0)
    {
    }

    public function modifyQuery($query, SearchTerm $search): void
    {
        $operator = '=';
        if ($search->operator() == 'neq') {
            $operator = '!=';
        }

        $searchTerm = true;
        if ($search->term() == 'false') {
            $searchTerm = false;
        }

        $query->where($search->column(), $operator, $searchTerm ? $this->trueVal : $this->falseVal);
    }

    public function operators(): array
    {
        return ['eq', 'neq'];
    }
}
