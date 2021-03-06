<?php

namespace App\Models;

use App\Cart\Money;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;
use App\Models\Collections\ProductVariationCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariation extends Model
{
    use HasFactory, HasPrice;

    public function getPriceAttribute($value)
    {
        if ($value === null) {
            return $this->product->price;
        }

        return new Money($value);
    }

    public function minStock($count)
    {
        return (int) min($this->stockCount(), $count);
    }

    public function priceVaries()
    {
        return $this->price->amount() !== $this->product->price->amount();
    }

    public function stockCount()
    {
        return $this->stock->first()->pivot->stock;
    }

    public function inStock()
    {
        return (bool) $this->stock->first()->pivot->in_stock;
    }

    public function stock()
    {
        return $this->belongsToMany(
            ProductVariation::class,
            'product_variation_stock_view'
        )->withPivot('stock', 'in_stock');
    }

    public function type()
    {
        return $this->hasOne(
            ProductVariationType::class,
            'id',
            'product_variation_type_id'
        );
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function newCollection($models = [])
    {
        return new ProductVariationCollection($models);
    }
}
