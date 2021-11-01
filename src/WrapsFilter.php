<?php

namespace UKFast\Sieve;

interface WrapsFilter extends ModifiesQueries
{
    public function wrap(ModifiesQueries $filter);
}