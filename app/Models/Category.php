<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\{HasChildren, Orderable};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasChildren, Orderable;

    protected $fillable = [
        'name',
        'order'
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
