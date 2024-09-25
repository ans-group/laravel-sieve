<?php

namespace Tests;

use Illuminate\Database\Eloquent\Builder;
use Tests\Mocks\Pet;
use UKFast\Sieve\SieveServiceProvider;

class SieveServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SieveServiceProvider::class,
        ];
    }

    /**
     * @test
     */
    public function defines_a_search_macro(): void
    {
        $query = Pet::query()->search();
        $this->assertInstanceOf(Builder::class, $query);
    }
}
