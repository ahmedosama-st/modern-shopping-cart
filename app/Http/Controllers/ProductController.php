<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Filtering\Filters\CategoryFilter;
use App\Http\Resources\{ProductIndexResource, ProductResource};

class ProductController extends Controller
{
    public function index()
    {
        return ProductIndexResource::collection(
            Product::with(['variations.stock'])->withFilters($this->filters())->paginate(10)
        );
    }

    public function filters()
    {
        return [
            'category' => new CategoryFilter()
        ];
    }

    public function show(Product $product)
    {
        $product->load(['variations.type', 'variations.product', 'variations.stock']);

        return new ProductResource($product);
    }
}
