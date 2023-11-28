<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'licence_number' => $this->licence_number,
            'shop_number' => $this->shop_number,
            'address' => $this->address,
            'uuid' => $this->uuid,
            //
            'category_id' => $this->category_id, #todo
            'province' => $this->province,
            'city' => $this->city,
            'owner' => new UserResource($this->owner),
            //
            'logo' => $this->logo,
        ];
    }
}
