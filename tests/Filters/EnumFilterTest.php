<?php

namespace Tests\Filters;

use Illuminate\Database\Query\Builder;
use Tests\TestCase;
use UKFast\Sieve\Exceptions\InvalidSearchTermException;
use UKFast\Sieve\Filters\EnumFilter;
use UKFast\Sieve\SearchTerm;

class EnumFilterTest extends TestCase
{
    /**
     * @test
     */
    public function throws_exception_on_invalid_search_term(): void
    {
        $filter = new EnumFilter(['a', 'b', 'c']);
        $search = new SearchTerm('letter', 'eq', 'letter', 'd');
        $query = $this->app->make(Builder::class);

        $this->expectException(InvalidSearchTermException::class);

        try {
            $filter->modifyQuery($query, $search);
        } catch (InvalidSearchTermException $e) {
            $this->assertEquals(['a', 'b', 'c'], $e->allowedValues);
            $this->assertEquals('letter', $e->property);
            throw $e;
        }
    }

    /**
     * @test
     */
    public function can_search_if_passed_a_valid_term(): void
    {
        $filter = new EnumFilter(['a', 'b', 'c']);
        $search = new SearchTerm('letter', 'eq', 'letter', 'a');
        $query = $this->app->make(Builder::class);
        $query->from("pets");

        $filter->modifyQuery($query, $search);

        $this->assertEquals(
            'select * from "pets" where "letter" = ?',
            $query->toSql()
        );
    }
}
