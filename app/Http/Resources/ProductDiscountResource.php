<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'good_count_from' => $this->good_count_from,
            'good_count_to' => $this->good_count_to,
            'percent' => $this->percent,
            'price' => $this->price,
        ];
    }
}
