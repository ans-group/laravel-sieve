<?php

namespace Tests\Mocks;

use UKFast\Sieve\ModifiesQueries;
use UKFast\Sieve\SearchTerm;
use UKFast\Sieve\WrapsFilter;

class PenceWrapper implements WrapsFilter
{
    protected ModifiesQueries $filter;

    public function wrap(ModifiesQueries $filter): void
    {
        $this->filter = $filter;
    }

    public function getWrapped(): ModifiesQueries
    {
        return $this->filter;
    }

    public function modifyQuery($query, SearchTerm $search): void
    {
        $this->filter->modifyQuery($query, new SearchTerm(
            $search->property(),
            $search->operator(),
            $search->column(),
            $search->term() * 100
        ));
    }

    public function operators()
    {
        return $this->filter->operators();
    }
}
