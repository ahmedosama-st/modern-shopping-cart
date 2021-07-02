<?php

namespace App\Models;

use App\Filtering\Filterar;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeWithFilters(Builder $builder, $filters = [])
    {
        return (new Filterar(
            request()
        ))->apply($builder, $filters);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
