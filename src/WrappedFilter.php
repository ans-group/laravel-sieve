<?php

namespace UKFast\Sieve;

class WrappedFilter implements ModifiesQueries
{
    protected ModifiesQueries $filter;

    public $column = '';

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function modifyQuery($query, SearchTerm $search)
    {
        return $this->filter->modifyQuery($query, $search);
    }

    public function operators()
    {
        return $this->filter->operators();
    }

    public function getFilter()
    {
        return $this->filter;
    }
}
