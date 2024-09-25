<?php

namespace Tests\Filters;

use Illuminate\Database\Query\Builder;
use Tests\TestCase;
use UKFast\Sieve\Filters\StringFilter;
use UKFast\Sieve\SearchTerm;

class StringFilterTest extends TestCase
{
    /**
     * @test
     */
    public function correctly_applies_eq_operator(): void
    {
        $search = new SearchTerm('name', 'eq', 'name', 'Bob');
        $builder = app(Builder::class);

        (new StringFilter())->modifyQuery($builder, $search);
        $where = $builder->wheres[0];

        $this->assertEquals('name', $where['column']);
        $this->assertEquals('=', $where['operator']);
        $this->assertEquals('Bob', $where['value']);
    }

    /**
     * @test
     */
    public function correctly_applies_neq_operator(): void
    {
        $search = new SearchTerm('name', 'neq', 'name', 'Bob');
        $builder = app(Builder::class);

        (new StringFilter())->modifyQuery($builder, $search);
        $where = $builder->wheres[0];

        $this->assertEquals('name', $where['column']);
        $this->assertEquals('!=', $where['operator']);
        $this->assertEquals('Bob', $where['value']);
    }

    /**
     * @test
     */
    public function correctly_applies_in_operator(): void
    {
        $search = new SearchTerm('name', 'in', 'name', 'Bob,James');
        $builder = app(Builder::class);

        (new StringFilter())->modifyQuery($builder, $search);
        $where = $builder->wheres[0];

        $this->assertEquals('name', $where['column']);
        $this->assertEquals('In', $where['type']);
        $this->assertEquals(['Bob', 'James'], $where['values']);
    }

    /**
     * @test
     */
    public function correctly_applies_nin_operator(): void
    {
        $search = new SearchTerm('name', 'nin', 'name', 'Bob,James');
        $builder = app(Builder::class);

        (new StringFilter())->modifyQuery($builder, $search);
        $where = $builder->wheres[0];

        $this->assertEquals('name', $where['column']);
        $this->assertEquals('NotIn', $where['type']);
        $this->assertEquals(['Bob', 'James'], $where['values']);
    }

    /**
     * @test
     */
    public function correctly_applies_lk_operator(): void
    {
        $search = new SearchTerm('name', 'lk', 'name', '*t\\\\est\\*');
        $builder = app(Builder::class);

        (new StringFilter())->modifyQuery($builder, $search);
        $this->assertEquals('select * where "name" LIKE ?', $builder->toSql());
        $this->assertEquals(['%t\est*'], $builder->getBindings());
    }

    /**
     * @test
     */
    public function correctly_applies_nlk_operator(): void
    {
        $search = new SearchTerm('name', 'nlk', 'name', '*t\\\\est\\*');
        $builder = app(Builder::class);

        (new StringFilter())->modifyQuery($builder, $search);
        $this->assertEquals('select * where "name" NOT LIKE ?', $builder->toSql());
        $this->assertEquals(['%t\est*'], $builder->getBindings());
    }
}
