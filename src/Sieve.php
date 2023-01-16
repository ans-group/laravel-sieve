<?php

namespace UKFast\Sieve;

use Illuminate\Http\Request;

class Sieve
{
    protected $filters = [];

    protected $request;

    protected $defaultSort = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function configure($callback)
    {
        foreach ($callback(new FilterBuilder) as $prop => $filter) {
            $this->addFilter($prop, $filter);
        }
    }

    public function addFilter($property, $filter)
    {
        $this->filters[] = compact('property', 'filter');
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function apply($queryBuilder)
    {
        foreach ($this->getFilters() as $sieveFilter) {
            /** @var ModifiesQueries */
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

        if ($this->request->has('sort')) {
            $this->applyRequestSorts($queryBuilder);
        } elseif ($this->defaultSort) {
            $this->applyDefaultSort($queryBuilder);
        }

        return $this;
    }

    public function getSort(): ?string
    {
        return $this->request->get("sort") ?? $this->defaultSort;
    }

    public function setDefaultSort($property = 'id', $direction = 'asc'): Sieve
    {
        $this->defaultSort = $property . ':' . $direction;

        return $this;
    }

    protected function applyRequestSorts($queryBuilder): void {
        $sorts = explode(',', $this->getSort());
        foreach ($sorts as $sort) {
            $property = explode(':', $sort)[0];

            $filterRule = collect($this->getFilters())->firstWhere('property', $property);
            if (!$filterRule) {
                continue;
            }

            $column = $property;
            $filter = $filterRule['filter'];
            while ($filter instanceof WrapsFilter) {
                if ($filter instanceof MapFilter) {
                    $column = $filter->target();
                    break;
                }

                $filter = $filter->getWrapped();
            }

            if (str_contains($column, ".")) {
                continue;
            }

            if ($sort == "$property:desc") {
                $queryBuilder->orderBy($column, "desc");
            }

            if ($sort == "$property:asc" || $sort == $property) {
                $queryBuilder->orderBy($column, "asc");
            }

            if ($sort == "$property:asc_nulls_last") {
                $queryBuilder->orderByRaw("ISNULL($column) asc")
                    ->orderBy($column, 'asc');
            }

            if ($sort == "$property:desc_nulls_first") {
                $queryBuilder->orderByRaw("ISNULL($column) desc")
                    ->orderBy($column, 'desc');
            }
        }
    }

    protected function applyDefaultSort($queryBuilder): void
    {
        list($column, $direction) = explode(':', $this->defaultSort);
        $queryBuilder->orderBy($column, $direction);
    }
}
