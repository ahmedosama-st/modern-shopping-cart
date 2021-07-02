<?php

namespace App\Filtering;

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
        foreach ($filters as $key => $filter) {
            if (!$filter instanceof Filter || !$this->request->has($key)) {
                continue;
            }

            $filter->apply($builder, $this->request->get($key));
        }

        return $builder;
    }
}
