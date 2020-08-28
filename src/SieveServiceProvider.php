<?php

namespace UKFast\Sieve;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class SieveServiceProvider extends ServiceProvider
{
    public function register()
    {
        $app = $this->app;

        Builder::macro('search', function () use ($app) {
            $model = $this->getModel();

            $sieve = $model->sieve($app->make(Sieve::class));

            $sieve->apply($this->getQuery());
        });
    }
}
