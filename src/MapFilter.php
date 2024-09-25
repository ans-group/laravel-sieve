<?php

namespace UKFast\Sieve;

class MapFilter implements WrapsFilter
{
    protected ModifiesQueries $filter;

    public function __construct(protected $column)
    {
    }

    public function wrap(ModifiesQueries $filter): void
    {
        $this->filter = $filter;
    }

    public function getWrapped(): ModifiesQueries
    {
        return $this->filter;
    }

    public function target()
    {
        return $this->column;
    }

    public function modifyQuery($query, SearchTerm $search): void
    {
        if (str_contains((string) $this->column, '.')) {
            $parts = explode(".", (string) $this->column);
            $relCol = array_pop($parts);
            $relationship = implode(".", $parts);

            $relSearch = new SearchTerm(
                $search->property(),
                $search->operator(),
                $relCol,
                $search->term()
            );
            $query->whereHas($relationship, function ($query) use ($relSearch): void {
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

    public function getFilter(): \UKFast\Sieve\ModifiesQueries
    {
        return $this->filter;
    }
}
