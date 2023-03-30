<?php

namespace UKFast\Sieve\Filters;

use UKFast\Sieve\ModifiesQueries;
use UKFast\Sieve\SearchTerm;

class StringFilter implements ModifiesQueries
{
    public function modifyQuery($query, SearchTerm $search)
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
        if ($search->operator() == 'lk') {
            $query->where($search->column(), 'LIKE', $this->prepareLike($search->term()));
        }
        if ($search->operator() == 'nlk') {
            $query->where($search->column(), 'NOT LIKE', $this->prepareLike($search->term()));
        }
    }

    protected function prepareLike($term)
    {
        $prepared = "";
        for ($i = 0; $i < strlen($term); $i++) {
            $char = $term[$i];
            $shouldEscape = $this->shouldEscape($term, $i);

            if ($char == '\\' && !$shouldEscape) {
                continue;
            }

            if ($char == '*' && !$shouldEscape) {
                $prepared .= '%';
                continue;
            }

            $prepared .= $char;
        }

        return $prepared;
    }

    private function shouldEscape($string, $pos)
    {
        if ($pos == 0) {
            return false;
        }

        if ($string[$pos-1] == '\\') {
            if ($this->shouldEscape($string, $pos-1)) {
                return false;
            }

            return true;
        }

        return false;
    }
    
    public function operators()
    {
        return ['eq', 'neq', 'in', 'nin', 'lk', 'nlk'];
    }
}
