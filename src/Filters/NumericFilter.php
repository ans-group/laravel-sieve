<?php

namespace UKFast\Sieve\Filters;

use UKFast\Sieve\ModifiesQueries;
use UKFast\Sieve\SearchTerm;

class NumericFilter implements ModifiesQueries
{
    public function modifyQuery($query, SearchTerm $search): void
    {
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
        if ($search->operator() == 'lt') {
            $query->where($search->column(), '<', $search->term());
        }
        if ($search->operator() == 'gt') {
            $query->where($search->column(), '>', $search->term());
        }
        if ($search->operator() == 'lte') {
            $query->where($search->column(), '<=', $search->term());
        }
        if ($search->operator() == 'gte') {
            $query->where($search->column(), '>=', $search->term());
        }
    }

    public function operators(): array
    {
        return ['eq', 'neq', 'in', 'nin', 'lt', 'gt', 'lte', 'gte'];
    }
}
