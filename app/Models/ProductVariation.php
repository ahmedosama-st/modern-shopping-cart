<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariation extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->hasOne(
            ProductVariationType::class,
            'id',
            'product_variation_type_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
