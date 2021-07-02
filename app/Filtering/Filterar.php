<?php

namespace App\Filtering;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Filtering\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class Filterar
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder, array $filters)
    {
        foreach ($this->limitFilters($filters) as $key => $filter) {
            if (!$filter instanceof Filter) {
                continue;
            }

            $filter->apply($builder, $this->request->get($key));
        }

        return $builder;
    }

    protected function limitFilters(array $filters)
    {
        return Arr::only(
            $filters,
            array_keys($this->request->all())
        );
    }
}
