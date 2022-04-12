<?php

namespace UKFast\Sieve\Filters;

use UKFast\Sieve\ModifiesQueries;
use UKFast\Sieve\SearchTerm;

class DateFilter implements ModifiesQueries
{
    public function modifyQuery($query, SearchTerm $search)
    {
        if ($search->operator() == 'eq') {
            if ($search->term() == 'null') {
                $query->whereNull($search->column());
            } else {
                $query->where($search->column(), $search->term());
            }
        }
        if ($search->operator() == 'neq') {
            if ($search->term() == 'null') {
                $query->whereNotNull($search->column());
            } else {
                $query->where($search->column(), '!=', $search->term());
            }
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
    }

    public function operators()
    {
        return ['eq', 'neq', 'in', 'nin', 'lt', 'gt'];
    }
}
