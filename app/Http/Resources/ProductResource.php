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
            'id' => $this->unique_id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'price' => $this->price,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'category' => new CategoryResource($this->category),
            'expire_at' => $this->expire_at,
            'expire_soon' => $this->expire_soon
        ];
    }
}
