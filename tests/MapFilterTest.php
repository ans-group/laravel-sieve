<?php

namespace Test;

use Tests\Mocks\Pet;
use Tests\TestCase;
use UKFast\Sieve\Filters\StringFilter;
use UKFast\Sieve\MapFilter;
use UKFast\Sieve\SearchTerm;
use PHPUnit\Framework\Attributes\Test;

class MapFilterTest extends TestCase
{
    #[Test]
    public function can_target_relationships(): void
    {
        $mapFilter = new MapFilter('owner.id');
        $mapFilter->wrap(new StringFilter());

        $eloquentBuilder = Pet::query();
        $mapFilter->modifyQuery($eloquentBuilder, new SearchTerm(
            'owner_id',
            'eq',
            'owner_id',
            1
        ));


        $builder = $eloquentBuilder->getQuery();
        $this->assertEquals(
            'select * from "pets" where exists ' .
            '(select * from "owners" where "pets"."owner_id" = "owners"."id" and "id" = ?)',
            $builder->toSql()
        );
    }

    #[Test]
    public function can_target_columns_in_the_same_table(): void
    {
        $mapFilter = new MapFilter('oid');
        $mapFilter->wrap(new StringFilter());

        $eloquentBuilder = Pet::query();
        $mapFilter->modifyQuery($eloquentBuilder, new SearchTerm(
            'owner_id',
            'eq',
            'owner_id',
            1
        ));


        $builder = $eloquentBuilder->getQuery();
        $this->assertEquals(
            'select * from "pets" where "oid" = ?',
            $builder->toSql()
        );
    }

    #[Test]
    public function can_target_nested_relationships(): void
    {

        $mapFilter = new MapFilter('owner.card.id');
        $mapFilter->wrap(new StringFilter());

        $eloquentBuilder = Pet::query();
        $mapFilter->modifyQuery($eloquentBuilder, new SearchTerm(
            'card_id',
            'eq',
            'card_id',
            1
        ));


        $builder = $eloquentBuilder->getQuery();
        $this->assertEquals(
            'select * from "pets" where' .
            ' exists (select * from "owners" where "pets"."owner_id" = "owners"."id" and' .
            ' exists (select * from "cards" where "owners"."id" = "cards"."owner_id" and "id" = ?))',
            $builder->toSql()
        );
    }
}
