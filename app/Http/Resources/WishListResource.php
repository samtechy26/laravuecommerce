<?php

namespace App\Http\Resources;

use App\Helper\Wish;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        [$products, $wishes] = $this->resource;

        return [
            'count' => Wish::getCount(),
            'items' => $wishes,
            'product' => ProductResource::collection($products)
        ];
    }
}
