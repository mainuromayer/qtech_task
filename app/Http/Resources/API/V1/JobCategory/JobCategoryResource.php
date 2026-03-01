<?php

namespace App\Http\Resources\API\V1\JobCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobCategoryResource extends JsonResource
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
            'icon' => $this->icon,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
        ];
    }
}