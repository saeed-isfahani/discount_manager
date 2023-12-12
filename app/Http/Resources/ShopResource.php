<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'location' => $this->location,
            //
            'category' => $this->category,
            'province' => $this->province,
            'city' => $this->city,
            'owner' => new UserResource($this->owner),
            //
            'logo' => Storage::url($this->logo),
            //
            'created_at' => $this->created_at,
        ];
    }
}
