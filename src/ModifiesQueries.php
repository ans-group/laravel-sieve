<?php

namespace UKFast\Sieve;

use Illuminate\Database\Query\Builder;

interface ModifiesQueries
{
    /**
     * Applies a search term to a query
     * @return void
     */
    public function modifyQuery(Builder $query, SearchTerm $search);

    /**
     * Returns a list of available operators
     * @return array
     */
    public function operators();
}
