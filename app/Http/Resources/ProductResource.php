<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'average_rating' => $this->average_rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product_images' => $this->whenLoaded('product_images', function () {
                return ProductImageResource::collection($this->product_images);
            }),
            'orderItems' => $this->whenLoaded('orderItems', function () {
                return OrderItemResource::collection($this->orderItems);
            }),
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand_id' => $this->brand_id,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'sizes' => $this->whenLoaded('sizes', function () {
                return SizeResource::collection($this->sizes);
            }),
            'colors' => $this->whenLoaded('colors', function () {
                return SizeResource::collection($this->colors);
            }),
            'ratings' => $this->whenLoaded('ratings', function () {
                return RatingResource::collection($this->ratings);})
        ];
    }
}
