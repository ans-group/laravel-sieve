<?php

namespace App\Sieve;

class SearchTerm
{
    /**
     * @var string $operator
     */
    protected $operator;

    /**
     * @property string $property
     */
    protected $property;

    /**
     * @property string $column
     */
    protected $column;

    /**
     * @property string $term
     */
    protected $term;

    /**
     * @param string $property
     * @param string $operator
     * @param string $column
     * @param string $term
     */
    public function __construct($property, $operator, $column, $term)
    {
        $this->property = $property;
        $this->operator = $operator;
        $this->column = $column;
        $this->term = $term;
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