<?php

namespace Tests;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Tests\Mocks\Pet;
use UKFast\Sieve\Filters\StringFilter;
use UKFast\Sieve\Sieve;

class SieveTest extends TestCase
{
    /**
     * @test
     */
    public function filters_and_sorts()
    {
        $request = Request::create('/', 'GET', [
            'name:in' => 'Snoopy,Hobbes',
            'sort'    => 'name:desc'
        ]);

        $seive = new Sieve($request);
        $seive->configure(fn ($builder) => [
            'name' => $builder->string(),
        ]);

        /** @var Builder */
        $builder = $this->app->make(Builder::class);
        $builder->from('pets');

        $seive->apply($builder);

        $this->assertEquals(
            'select * from "pets" where "name" in (?, ?) order by "name" desc',
            $builder->toSql()
        );
    }

    /**
     * @test
     */
    public function set_default_sort_filter()
    {
        $request = Request::create('/');

        $sieve = new Sieve($request);
        $sieve->setDefaultSort('name', 'desc');

        $this->assertEquals($sieve->getSort(), 'name:desc');
    }

    /**
     * @test
     */
    public function applies_sieve_sorts_to_a_query_builder_asc()
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:asc',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter);

        /** @var Builder */
        $builder = $this->app->make(Builder::class);
        $builder->from('pets');

        $seive->apply($builder);

        $this->assertEquals(
            'select * from "pets" order by "name" asc',
            $builder->toSql()
        );
    }

    /**
     * @test
     */
    public function applies_sieve_sorts_to_a_query_builder_asc_nulls_last()
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:asc_nulls_last',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter);

        /** @var Builder */
        $builder = $this->app->make(Builder::class);
        $builder->from('pets');

        $seive->apply($builder);

        $this->assertEquals(
            'select * from "pets" order by ISNULL(name) asc, "name" asc',
            $builder->toSql()
        );
    }

    /**
     * @test
     */
    public function applies_sieve_sorts_to_a_query_builder_desc_nulls_first()
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:desc_nulls_first',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter);

        /** @var Builder */
        $builder = $this->app->make(Builder::class);
        $builder->from('pets');

        $seive->apply($builder);

        $this->assertEquals(
            'select * from "pets" order by ISNULL(name) desc, "name" desc',
            $builder->toSql()
        );
    }

    /**
     * @test
     */
    public function ignores_undefined_sort()
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:desc',
        ]);

        $seive = new Sieve($request);

        /** @var Builder */
        $builder = $this->app->make(Builder::class);

        $seive->apply($builder);

        $this->assertEquals(null, $builder->orders);
    }

    /**
     * @test
     */
    public function ignores_invalid_sort_direction()
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:foo',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter);

        /** @var Builder */
        $builder = $this->app->make(Builder::class);

        $seive->apply($builder);

        $this->assertEquals(null, $builder->orders);
    }

    /**
     * @test
     */
    public function expands_no_eplicit_operator_to_eq()
    {
        $request = Request::create('/', 'GET', [
            'name' => 'Snoopy',
        ]);

        $seive = new Sieve($request);
        $seive->configure(fn ($builder) => [
            'name' => $builder->string(),
        ]);

        /** @var Builder */
        $builder = $this->app->make(Builder::class);
        $builder->from('pets');

        $seive->apply($builder);

        $this->assertEquals(
            'select * from "pets" where "name" = ?',
            $builder->toSql()
        );
    }

    /**
     * @test
     */
    public function sorts_can_be_remapped()
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:asc',
        ]);

        $seive = new Sieve($request);
        $seive->configure(fn ($builder) => [
            'name' => $builder->for('pname')->string(),
        ]);

        /** @var Builder */
        $builder = Pet::query();

        $seive->apply($builder);

        $this->assertEquals(
            'select * from `pets` order by `pname` asc',
            $builder->toSql()
        );
    }

    /**
     * Not figured out a good way to do this with eloquent yet
     * @test
     */
    public function ignores_sorts_on_relationships()
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'owner_name:asc',
        ]);

        $seive = new Sieve($request);
        $seive->configure(fn ($builder) => [
            'owner_name' => $builder->for('owner.name')->string(),
        ]);

        /** @var Builder */
        $builder = Pet::query();

        $seive->apply($builder);

        $this->assertEquals(
            'select * from `pets`',
            $builder->toSql()
        );
    }
}
