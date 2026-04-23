<?php

namespace Tests\Filters;

use Illuminate\Database\Query\Builder;
use Tests\Mocks\Pet;
use Tests\TestCase;
use UKFast\Sieve\Filters\NumericFilter;
use UKFast\Sieve\SearchTerm;
use PHPUnit\Framework\Attributes\Test;

class NumericFilterTest extends TestCase
{
    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    public function testCanFilterByLte(): void
    {
        $query = Pet::query()->getQuery();
        $search = new SearchTerm('id', 'lte', 'id', '5');
        (new NumericFilter())->modifyQuery($query, $search);

        $this->assertEquals(
            'select * from "pets" where "id" <= ?',
            $query->toSql()
        );
    }

    public function testCanFilterByGte(): void
    {
        $query = Pet::query()->getQuery();
        $search = new SearchTerm('id', 'gte', 'id', '5');
        (new NumericFilter())->modifyQuery($query, $search);

        $this->assertEquals(
            'select * from "pets" where "id" >= ?',
            $query->toSql()
        );
    }
}
