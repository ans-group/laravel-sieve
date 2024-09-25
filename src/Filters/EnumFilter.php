<?php

namespace UKFast\Sieve\Filters;

use UKFast\Sieve\Exceptions\InvalidSearchTermException;
use UKFast\Sieve\ModifiesQueries;
use UKFast\Sieve\SearchTerm;

class EnumFilter implements ModifiesQueries
{
    public function __construct(protected $allowedValues)
    {
    }

    public function modifyQuery($query, SearchTerm $search): void
    {
        $terms = [$search->term()];
        if ($search->operator() == 'nin' || $search->operator() == 'in') {
            $terms = explode(",", $search->term());
        }
        foreach ($terms as $term) {
            if (!in_array($term, $this->allowedValues())) {
                $exception = new InvalidSearchTermException(
                    "{$search->property()} must be one of " . implode(", ", $this->allowedValues)
                );
                $exception->allowedValues = $this->allowedValues;
                $exception->property = $search->property();
                throw $exception;
            }
        }

        (new StringFilter())->modifyQuery($query, $search);
    }

    public function operators(): array
    {
        return ['in', 'eq', 'neq', 'nin'];
    }

    public function allowedValues()
    {
        return $this->allowedValues;
    }
}
