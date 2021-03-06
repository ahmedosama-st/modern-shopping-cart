<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->resource instanceof Collection) {
            return ProductVariationResource::collection($this->resource);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->formattedPrice,
            'type' => $this->type->name,
            'price_varies' => $this->priceVaries(),
            'stock_count' => (int) $this->stockCount(),
            'in_stock' => (bool) $this->inStock(),
            'product' => new ProductIndexResource($this->product)
        ];
    }
}
