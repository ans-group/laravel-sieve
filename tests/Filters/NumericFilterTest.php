<?php

namespace Tests\Filters;

use Illuminate\Database\Query\Builder;
use Tests\TestCase;
use UKFast\Sieve\Filters\NumericFilter;
use UKFast\Sieve\SearchTerm;

class NumericFilterTest extends TestCase
{
    /**
     * @test
     */
    public function correctly_applies_eq_operator(): void
    {
        $search = new SearchTerm('name', 'eq', 'age', 1);
        $builder = app(Builder::class);

        (new NumericFilter())->modifyQuery($builder, $search);
        $where = $builder->wheres[0];

        $this->assertEquals('age', $where['column']);
        $this->assertEquals('=', $where['operator']);
        $this->assertEquals(1, $where['value']);
    }

    /**
     * @test
     */
    public function correctly_applies_neq_operator(): void
    {
        $search = new SearchTerm('age', 'neq', 'age', 1);
        $builder = app(Builder::class);

        (new NumericFilter())->modifyQuery($builder, $search);
        $where = $builder->wheres[0];

        $this->assertEquals('age', $where['column']);
        $this->assertEquals('!=', $where['operator']);
        $this->assertEquals(1, $where['value']);
    }

    /**
     * @test
     */
    public function correctly_applies_in_operator(): void
    {
        $search = new SearchTerm('age', 'in', 'age', '1,2');
        $builder = app(Builder::class);

        (new NumericFilter())->modifyQuery($builder, $search);
        $where = $builder->wheres[0];

        $this->assertEquals('age', $where['column']);
        $this->assertEquals('In', $where['type']);
        $this->assertEquals([1, 2], $where['values']);
    }

    /**
     * @test
     */
    public function correctly_applies_nin_operator(): void
    {
        $search = new SearchTerm('age', 'nin', 'age', '1,2');
        $builder = app(Builder::class);

        (new NumericFilter())->modifyQuery($builder, $search);
        $where = $builder->wheres[0];

        $this->assertEquals('age', $where['column']);
        $this->assertEquals('NotIn', $where['type']);
        $this->assertEquals([1, 2], $where['values']);
    }
}
