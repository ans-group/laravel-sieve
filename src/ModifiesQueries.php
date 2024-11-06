<?php

namespace UKFast\Sieve;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface ModifiesQueries
{
    /**
     * Applies a search term to a query
     * @template TModel of Model
     * @param EloquentBuilder<TModel>|QueryBuilder $query
     * @return void
     */
    public function modifyQuery($query, SearchTerm $search);

    /**
     * Returns a list of available operators
     * @return array
     */
    public function operators();
}
