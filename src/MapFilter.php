<?php

namespace UKFast\Sieve;

class MapFilter implements WrapsFilter
{
    protected ModifiesQueries $filter;

    protected $column = '';

    public function __construct($column)
    {
        $this->column = $column;
    }

    public function wrap(ModifiesQueries $filter)
    {
        $this->filter = $filter;
    }

    public function modifyQuery($query, SearchTerm $search)
    {
        if (strpos($this->column, '.') !== false) {
            [$relationship, $relCol] = explode(".", $this->column);
            $relSearch = new SearchTerm(
                $search->property(),
                $search->operator(),
                $relCol,
                $search->term()
            );
            $query->whereHas($relationship, function ($query) use ($relSearch) {
                $this->filter->modifyQuery($query, $relSearch);
            });
            return;
        }

        $newSearch = new SearchTerm(
            $search->property(),
            $search->operator(),
            $this->column,
            $search->term()
        );

        $this->filter->modifyQuery($query, $newSearch);
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
