<?php

namespace UKFast\Sieve\Filters;

use Illuminate\Database\Query\Builder;
use UKFast\Sieve\ModifiesQueries;

class BooleanFilter implements ModifiesQueries
{
    protected $trueVal;

    protected $falseVal;

    public function __construct($trueVal = 1, $falseVal = 0)
    {
        $this->trueVal = $trueVal;    
        $this->falseVal = $falseVal;    
    }

    public function modifyQuery($query, SearchTerm $search)
    {
        $op = '=';
        if ($search->operator() == 'neq') {
            $op = '!=';
        }
        $query->where($search->column(), $op, $search->term() == true ? $this->trueVal : $this->falseVal);
    }

    public function operators()
    {
        return ['eq', 'neq'];
    }
}