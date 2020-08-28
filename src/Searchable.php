<?php

namespace UKFast\Sieve;

interface Searchable
{
    /**
     * Configures a sieve instance so that query builders
     * can be modified
     * 
     * @return void
     */
    public function sieve(Sieve $sieve);
}
