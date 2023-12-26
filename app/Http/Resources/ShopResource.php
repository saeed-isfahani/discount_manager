<?php

namespace App\Http\Resources;

use App\Policies\CategoryPolicy;
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
            'category' => new CategoryResource($this->category),
            'province' => new ProvinceCityResource($this->province),
            'city' => new ProvinceCityResource($this->city),
            'owner' => new UserResource($this->owner),
            'status' => $this->status,
            //
            'logo' => Storage::url($this->logo),
            //
            'created_at' => $this->created_at,
        ];
    }
}
