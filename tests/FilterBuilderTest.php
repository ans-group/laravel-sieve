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
use PHPUnit\Framework\Attributes\Test;

class FilterBuilderTest extends TestCase
{
    #[Test]
    public function can_build_strings(): void
    {
        $builder = new FilterBuilder();

        $this->assertInstanceOf(StringFilter::class, $builder->string());
    }

    #[Test]
    public function can_build_enum(): void
    {
        $builder = new FilterBuilder();

        $this->assertInstanceOf(EnumFilter::class, $builder->enum(['a', 'b']));
    }

    #[Test]
    public function can_build_numeric(): void
    {
        $builder = new FilterBuilder();

        $this->assertInstanceOf(NumericFilter::class, $builder->numeric());
    }

    #[Test]
    public function can_build_date(): void
    {
        $builder = new FilterBuilder();

        $this->assertInstanceOf(DateFilter::class, $builder->date());
    }

    #[Test]
    public function can_build_boolean(): void
    {
        $builder = new FilterBuilder();

        $this->assertInstanceOf(BooleanFilter::class, $builder->boolean());
    }


    #[Test]
    public function can_build_custom_filters(): void
    {
        $builder = new FilterBuilder();

        $this->assertInstanceOf(NoOpFilter::class, $builder->custom(new NoOpFilter()));
    }

    #[Test]
    public function can_wrap_filters(): void
    {
        $builder = new FilterBuilder();
        $wrapped = $builder->wrap(new PenceWrapper())->string();

        $this->assertInstanceOf(PenceWrapper::class, $wrapped);
    }

    #[Test]
    public function can_wrap_multiple_filters(): void
    {
        $builder = new FilterBuilder();
        $wrapped = $builder->wrap(new PenceWrapper())->wrap(new MapFilter('target'))->string();

        $this->assertInstanceOf(MapFilter::class, $wrapped);
    }
}
