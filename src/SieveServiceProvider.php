<?php

namespace UKFast\Sieve;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class SieveServiceProvider extends ServiceProvider
{
    public function register()
    {
        Builder::macro('search', function () {
            /** @var Searchable */
            $model = $this->getModel();

            $sieve = new Sieve;
            $model->sieve($sieve);

            foreach ($sieve->getFilters() as $filter) {
                /** @var ModifiesQueries */
                $filter = $filter['filter'];
                $property = $filter['property'];

                foreach ($filter->operators() as $operator) {
                    if (!Request::has("$property:$operator")) {
                        continue;
                    }

                    $term = Request::get("$property:$operator");
                    
                    $search = new SearchTerm($property, $operator, $property, $term);
                    $filter->modifyQuery($this->getQuery(), $search);
                }
            }

            return $this;
        });
    }
}
