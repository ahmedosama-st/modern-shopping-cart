<?php

namespace App\Models\Traits;

use App\Filtering\Filterar;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeWithFilters(Builder $builder, $filters = [])
    {
        return (new Filterar(
            request()
        ))->apply($builder, $filters);
    }
}
