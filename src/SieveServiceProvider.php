<?php

namespace UKFast\Sieve;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class SieveServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $app = $this->app;

        Builder::macro('search', function () use ($app): object {
            $model = $this->getModel();

            $sieve = $app->make(Sieve::class);
            $model->sieve($sieve);

            $sieve->apply($this);
            return $this;
        });
    }
}
