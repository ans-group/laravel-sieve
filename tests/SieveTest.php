<?php

namespace Tests;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use UKFast\Sieve\Filters\StringFilter;
use UKFast\Sieve\Sieve;

class SieveTest extends TestCase
{
    /**
     * @test
     */
    public function applies_sieve_filters_to_a_query_builder()
    {
        $request = Request::create('/', 'GET', [
            'name:in' => 'Snoopy,Hobbes',
        ]);

        $seive = new Sieve($request);
        $seive->addFilter('name', new StringFilter);

        /** @var Builder */
        $builder = $this->app->make(Builder::class);

        $seive->apply($builder);

        $this->assertEquals(1, count($builder->wheres));

        $where = $builder->wheres[0];

        $this->assertEquals('In', $where['type']);
        $this->assertEquals('name', $where['column']);
        $this->assertEquals([
            'Snoopy',
            'Hobbes',
        ], $where['values']);
        $this->assertEquals('and', $where['boolean']);
    }

    /**
     * @test
     */
    public function set_default_sort_filter()
    {
        $request = Request::create('/');

        $sieve = new Sieve($request);
        $sieve->setDefaultSort('name','desc');

        $this->assertEquals($sieve->getSort(), 'name:desc');
    }
}
