<?php

namespace Tests;

use PHPUnit\Util\Filter;
use Tests\Mocks\NoOpFilter;
use Tests\Mocks\PenceWrapper;
use UKFast\Sieve\FilterBuilder;
use UKFast\Sieve\Filters\BooleanFilter;
use UKFast\Sieve\Filters\DateFilter;
use UKFast\Sieve\Filters\EnumFilter;
use UKFast\Sieve\Filters\NumericFilter;
use UKFast\Sieve\Filters\StringFilter;
use UKFast\Sieve\MapFilter;

class FilterBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function can_build_strings()
    {
        $builder = new FilterBuilder;

        $this->assertInstanceOf(StringFilter::class, $builder->string());
    }

    /**
     * @test
     */
    public function can_build_enum()
    {
        $builder = new FilterBuilder;

        $this->assertInstanceOf(EnumFilter::class, $builder->enum(['a', 'b']));
    }

    /**
     * @test
     */
    public function can_build_numeric()
    {
        $builder = new FilterBuilder;

        $this->assertInstanceOf(NumericFilter::class, $builder->numeric());
    }

    /**
     * @test
     */
    public function can_build_date()
    {
        $builder = new FilterBuilder;

        $this->assertInstanceOf(DateFilter::class, $builder->date());
    }

    /**
     * @test
     */
    public function can_build_boolean()
    {
        $builder = new FilterBuilder;

        $this->assertInstanceOf(BooleanFilter::class, $builder->boolean());
    }


    /**
     * @test
     */
    public function can_build_custom_filters()
    {
        $builder = new FilterBuilder;

        $this->assertInstanceOf(NoOpFilter::class, $builder->custom(new NoOpFilter));
    }

    /**
     * @test
     */
    public function can_wrap_filters()
    {
        $builder = new FilterBuilder;
        $wrapped = $builder->wrap(new PenceWrapper)->string();

        $this->assertInstanceOf(PenceWrapper::class, $wrapped);
    }

    /**
     * @test
     */
    public function can_wrap_multiple_filters()
    {
        $builder = new FilterBuilder;
        $wrapped = $builder->wrap(new PenceWrapper)->wrap(new MapFilter('target'))->string();

        $this->assertInstanceOf(MapFilter::class, $wrapped);
    }
}
