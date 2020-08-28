<?php

namespace UKFast\Sieve\Filters;

use App\Sieve\ModifiesQueries;
use App\Sieve\SearchTerm;
use Illuminate\Database\Query\Builder;

class StringFilter implements ModifiesQueries
{
    public function modifyQuery(Builder $query, SearchTerm $search)
    {
        if ($search->operator() == 'eq') {
            $query->where($search->column(), $search->term());
        }
        if ($search->operator() == 'neq') {
            $query->whereNot($search->column(), $search->term());
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
        return ['eq', 'neq', 'in', 'nin'];
    }
}
