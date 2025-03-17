<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginatedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'users' => SimpleResource::collection($this),
            'pagination' => [
                'total' => $this->total(),
                'perPage' => $this->perPage(),
                'currentPage' => $this->currentPage()
            ]
        ];
    }
}
