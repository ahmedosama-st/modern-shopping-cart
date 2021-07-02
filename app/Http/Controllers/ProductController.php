<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\{ProductIndexResource, ProductResource};

class ProductController extends Controller
{
    public function index()
    {
        return ProductIndexResource::collection(
            Product::paginate(10)
        );
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}
