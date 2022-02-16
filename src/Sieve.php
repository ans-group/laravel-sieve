<?php

namespace UKFast\Sieve;

use Illuminate\Http\Request;

class Sieve
{
    protected $filters = [];

    protected $sortable = [];

    protected $request;

    protected $defaultSort = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function configure($callback, array $sortable = [])
    {
        foreach ($callback(new FilterBuilder) as $prop => $filter) {
            $this->addFilter($prop, $filter);
        }

        foreach ($sortable as $sort) {
            $this->addSort($sort);
        }
    }

    public function addFilter($property, $filter)
    {
        $this->filters[] = compact('property', 'filter');
    }

    public function filters()
    {
        return new FilterBuilder;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function apply($queryBuilder)
    {
        foreach ($this->getFilters() as $sieveFilter) {
            /** @var Filter */
            $filter = $sieveFilter['filter'];
            $property = $sieveFilter['property'];

            foreach ($filter->operators() as $operator) {
                $eqFilter = $operator == 'eq' && $this->request->has($property);
                if (!$this->request->has("$property:$operator") && !$eqFilter) {
                    continue;
                }

                $term = $this->request->get("$property:$operator");
                if ($eqFilter) {
                    $term = $this->request->get($property);
                }

                $search = new SearchTerm($property, $operator, $property, $term);
                $filter->modifyQuery($queryBuilder, $search);
            }
        }

        if ($sort = $this->getSort()) {
            $queryBuilder->orderBy($sort['sortBy'], $sort['sortDirection']);
        }

        return $this;
    }

    public function setDefaultSort($property = 'id', $direction = 'asc'): Sieve
    {
        $this->sortable[] = $property;
        $this->defaultSort = $property . ':' . $direction;

        return $this;
    }

    public function addSort(string $sort)
    {
        $this->sortable[] = $sort;
    }

    public function getSort(): ?array
    {
        $sort = $this->request->get("sort") ?? $this->defaultSort ?? ':';

        list($sortBy, $sortDirection) = explode(':', $sort, 2);

        if ((in_array(strtolower($sortDirection), ['asc', 'desc'])) && (in_array($sortBy, $this->sortable))) {
            return compact('sortBy', 'sortDirection');
        }

        return null;
    }

    public function getSortable(): array
    {
        return $this->sortable;
    }
}
