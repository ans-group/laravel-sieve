<?php

namespace Test\Filters;

use Tests\Mocks\Pet;
use Tests\TestCase;
use UKFast\Sieve\Filters\BooleanFilter;
use UKFast\Sieve\SearchTerm;

class BooleanFilterTest extends TestCase
{
    /**
     * @test
     */
    public function can_filter_eq()
    {
        $filter = new BooleanFilter;
        $builder = Pet::query()->getQuery();
        $filter->modifyQuery($builder, $this->searchTerm('eq', true));

        $this->assertEquals(
            "select * from `pets` where `is_active` = ?",
            $builder->toSql()
        );
        $this->assertEquals([1], $builder->getBindings());
    }

    /**
     * @test
     */
    public function can_filter_neq()
    {
        $filter = new BooleanFilter;
        $builder = Pet::query()->getQuery();
        $filter->modifyQuery($builder, $this->searchTerm('neq', true));

        $this->assertEquals(
            "select * from `pets` where `is_active` != ?",
            $builder->toSql()
        );
        $this->assertEquals([1], $builder->getBindings());
    }

    /**
     * @test
     */
    public function can_override_true_and_false_val()
    {

        $filter = new BooleanFilter('yes', 'no');
        $builder = Pet::query()->getQuery();
        $filter->modifyQuery($builder, $this->searchTerm('eq', true));

        $this->assertEquals(
            "select * from `pets` where `is_active` = ?",
            $builder->toSql()
        );
        $this->assertEquals(['yes'], $builder->getBindings());
    }

    private function searchTerm($operator, $term)
    {
        return new SearchTerm('is_active', $operator, 'is_active', $term);
    }
}