<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'docs' => ShopResource::collection($this->collection),
            'pagination' => [
                'count' => $this->count(),
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'last_page' => $this->lastPage(),
                'current_page' => $this->currentPage(),
                'next_page_url' => $this->nextPageUrl(),
                'previous_page_url' => $this->previousPageUrl(),
            ],
        ];
    }
}
