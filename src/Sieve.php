<?php

namespace UKFast\Sieve;

use Illuminate\Http\Request;

class Sieve
{
    protected $filters = [];

    protected $request;

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
            /** @var WrappedFilter */
            $filter = $sieveFilter['filter'];
            $property = $sieveFilter['property'];

            $sort = $this->request->get("sort");

            foreach ($filter->operators() as $operator) {
                if (!$this->request->has("$property:$operator")) {
                    continue;
                }

                $term = $this->request->get("$property:$operator");
                
                $column = $property;
                if ($filter instanceof WrappedFilter && $filter->column) {
                    $column = $filter->column;
                }
                
                $search = new SearchTerm($property, $operator, $column, $term);

                if ($filter instanceof WrappedFilter && strpos($filter->column, '.') !== false) {
                    [$relationship, $relCol] = explode(".", $filter->column);
                    $relSearch = new SearchTerm($property, $operator, $relCol, $term);
                    $queryBuilder->whereHas($relationship, function ($query) use ($relCol, $relSearch, $filter) {
                        $filter->modifyQuery($query, $relSearch);
                    });
                    continue;
                }
                $filter->modifyQuery($queryBuilder, $search);
            }

            if ($sort == "$property:desc") {
                $queryBuilder->orderBy($property, "desc");
            }

            if ($sort == "$property:asc") {
                $queryBuilder->orderBy($property, "asc");
            }
        }

        return $this;
    }
}
