<?php

namespace App\Filtering\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    public function apply(Builder $builder, $value);
}
