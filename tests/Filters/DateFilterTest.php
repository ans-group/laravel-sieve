<?php

namespace Tests;

use Tests\Mocks\Pet;
use UKFast\Sieve\Filters\DateFilter;
use UKFast\Sieve\SearchTerm;

class DateFilterTest extends TestCase
{
    /**
     * @test
     */
    public function can_filter_by_eq()
    {
        $query = Pet::query()->getQuery();
        (new DateFilter)->modifyQuery($query, $this->searchTerm('eq', '2020-01-01T00:00:00+00:00'));
        
        $this->assertEquals(
            "select * from `pets` where `created_at` = ?",
            $query->toSql(),
        );
    }

    /**
     * @test
     */
    public function can_filter_by_neq()
    {
        $query = Pet::query()->getQuery();
        (new DateFilter)->modifyQuery($query, $this->searchTerm('neq', '2020-01-01T00:00:00+00:00'));
        
        $this->assertEquals(
            "select * from `pets` where `created_at` != ?",
            $query->toSql(),
        );
    }

    /**
     * @test
     */
    public function can_filter_by_in()
    {
        $query = Pet::query()->getQuery();
        $dates = implode(',', [
            '2020-01-01T00:00:00+00:00',
            '2020-01-01T00:00:00+00:00',
            '2020-01-01T00:00:00+00:00',
        ]);
        (new DateFilter)->modifyQuery($query, $this->searchTerm('in', $dates));
        
        $this->assertEquals(
            "select * from `pets` where `created_at` in (?, ?, ?)",
            $query->toSql(),
        );
    }

    /**
     * @test
     */
    public function can_filter_by_nin()
    {
        $query = Pet::query()->getQuery();
        $dates = implode(',', [
            '2020-01-01T00:00:00+00:00',
            '2020-01-01T00:00:00+00:00',
            '2020-01-01T00:00:00+00:00',
        ]);
        (new DateFilter)->modifyQuery($query, $this->searchTerm('nin', $dates));
        
        $this->assertEquals(
            "select * from `pets` where `created_at` not in (?, ?, ?)",
            $query->toSql(),
        );
    }

    /**
     * @test
     */
    public function can_filter_by_lt()
    {
        $query = Pet::query()->getQuery();
        (new DateFilter)->modifyQuery($query, $this->searchTerm('lt', '2020-01-01T00:00:00+00:00'));
        
        $this->assertEquals(
            "select * from `pets` where `created_at` < ?",
            $query->toSql(),
        );
    }

    /**
     * @test
     */
    public function can_filter_by_gt()
    {
        $query = Pet::query()->getQuery();
        (new DateFilter)->modifyQuery($query, $this->searchTerm('gt', '2020-01-01T00:00:00+00:00'));
        
        $this->assertEquals(
            "select * from `pets` where `created_at` > ?",
            $query->toSql(),
        );
    }

    /**
     * @test
     */
    public function can_filter_null_by_eq()
    {
        $query = Pet::query()->getQuery();
        (new DateFilter)->modifyQuery($query, $this->searchTerm('eq', null));

        $this->assertEquals(
            "select * from `pets` where `created_at` is null",
            $query->toSql(),
        );
    }

    /**
     * @test
     */
    public function can_filter_null_by_neq()
    {
        $query = Pet::query()->getQuery();
        (new DateFilter)->modifyQuery($query, $this->searchTerm('neq', null));

        $this->assertEquals(
            "select * from `pets` where `created_at` is not null",
            $query->toSql(),
        );
    }

    private function searchTerm($operator, $term)
    {
        return new SearchTerm('created_at', $operator, 'created_at', $term);
    }
}