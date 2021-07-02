<?php

namespace App\Filtering\Filters;

use App\Filtering\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter implements Filter
{
    public function apply(Builder $builder, $value)
    {
        return $builder->whereHas('categories', function ($builder) use ($value) {
            $builder->where('slug', $value);
        });
    }
}
