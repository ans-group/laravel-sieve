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
    public function filters_and_sorts(): void
    {
        $request = Request::create('/', 'GET', [
            'name:in' => 'Snoopy,Hobbes',
            'sort'    => 'name:desc'
        ]);

        $sieve = new Sieve($request);
        $sieve->configure(fn ($builder): array => [
            'name' => $builder->string(),
        ]);

        /** @var Builder */
        $builder = $this->app->make(Builder::class);
        $builder->from('pets');

        $sieve->apply($builder);

        $this->assertEquals(
            'select * from "pets" where "name" in (?, ?) order by "name" desc',
            $builder->toSql()
        );
    }

    /**
     * @test
     */
    public function set_default_sort_filter(): void
    {
        $request = Request::create('/');

        $sieve = new Sieve($request);
        $sieve->setDefaultSort('name', 'desc');

        $this->assertEquals($sieve->getSort(), 'name:desc');

        /** @var Builder */
        $builder = $this->app->make(Builder::class);
        $builder->from('pets');

        $sieve->apply($builder);

        $this->assertEquals(
            'select * from "pets" order by "name" desc',
            $builder->toSql()
        );
    }

    /**
     * @test
     */
    public function applies_sieve_sorts_to_a_query_builder_asc_by_default(): void
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter());

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
    public function applies_sieve_sorts_to_a_query_builder_asc(): void
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:asc',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter());

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
    public function applies_sieve_sorts_to_a_query_builder_asc_nulls_last(): void
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:asc_nulls_last',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter());

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
    public function applies_sieve_sorts_to_a_query_builder_desc_nulls_first(): void
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:desc_nulls_first',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter());

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
    public function allows_multiple_columns_to_be_ordered(): void
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'type:asc,name:desc',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('type', new StringFilter());
        $seive->addFilter('name', new StringFilter());

        /** @var Builder */
        $builder = $this->app->make(Builder::class);
        $builder->from('pets');

        $seive->apply($builder);

        $this->assertEquals(
            'select * from "pets" order by "type" asc, "name" desc',
            $builder->toSql()
        );
    }

    /**
     * @test
     */
    public function allows_multiple_columns_to_be_ordered_including_a_null_column(): void
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'type:asc,name:desc_nulls_first',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('type', new StringFilter());
        $seive->addFilter('name', new StringFilter());

        /** @var Builder */
        $builder = $this->app->make(Builder::class);
        $builder->from('pets');

        $seive->apply($builder);

        $this->assertEquals(
            'select * from "pets" order by "type" asc, ISNULL(name) desc, "name" desc',
            $builder->toSql()
        );
    }

    /**
     * @test
     */
    public function applies_order_by_in_order_when_sieve_config_order_is_different(): void
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'type:asc,name:desc',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter());
        $seive->addFilter('type', new StringFilter());

        /** @var Builder */
        $builder = $this->app->make(Builder::class);
        $builder->from('pets');

        $seive->apply($builder);

        $this->assertEquals(
            'select * from "pets" order by "type" asc, "name" desc',
            $builder->toSql()
        );
    }

    /**
     * @test
     */
    public function ignores_undefined_sort(): void
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
    public function ignores_invalid_sort_direction(): void
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:foo',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter());

        /** @var Builder */
        $builder = $this->app->make(Builder::class);

        $seive->apply($builder);

        $this->assertEquals(null, $builder->orders);
    }

    /**
     * @test
     */
    public function expands_no_eplicit_operator_to_eq(): void
    {
        $request = Request::create('/', 'GET', [
            'name' => 'Snoopy',
        ]);

        $seive = new Sieve($request);
        $seive->configure(fn ($builder): array => [
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
    public function sorts_can_be_remapped(): void
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'name:asc',
        ]);

        $seive = new Sieve($request);
        $seive->configure(fn ($builder): array => [
            'name' => $builder->for('pname')->string(),
        ]);

        /** @var Builder */
        $builder = Pet::query();

        $seive->apply($builder);

        $this->assertEquals(
            'select * from "pets" order by "pname" asc',
            $builder->toSql()
        );
    }

    /**
     * Not figured out a good way to do this with eloquent yet
     * @test
     */
    public function ignores_sorts_on_relationships(): void
    {
        $request = Request::create('/', 'GET', [
            'sort' => 'owner_name:asc',
        ]);

        $seive = new Sieve($request);
        $seive->configure(fn ($builder): array => [
            'owner_name' => $builder->for('owner.name')->string(),
        ]);

        /** @var Builder */
        $builder = Pet::query();

        $seive->apply($builder);

        $this->assertEquals(
            'select * from "pets"',
            $builder->toSql()
        );
    }
}
