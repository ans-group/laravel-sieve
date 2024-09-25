<?php

namespace UKFast\Sieve;

class SearchTerm
{
    /**
     * @param string $property
     * @param string $operator
     * @param string $column
     * @param string $term
     */
    public function __construct(protected $property, protected $operator, protected $column, protected $term)
    {
    }

    /**
     * @return string
     */
    public function operator()
    {
        return $this->operator;
    }

    /**
     * @return string
     */
    public function property()
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function column()
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function term()
    {
        return $this->term;
    }
}
