<?php

declare(strict_types=1);

namespace Tests\Mocks;

use UKFast\Sieve\ModifiesQueries;
use UKFast\Sieve\SearchTerm;

class NoOpFilter implements ModifiesQueries
{
    public function modifyQuery($query, SearchTerm $search)
    {
    }

    public function operators(): array
    {
        return [];
    }
}
