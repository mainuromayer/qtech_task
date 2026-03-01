<?php

namespace App\Http\Resources\API\V1\Job;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'description' => $this->description,
            'salary' => $this->salary,
            'location' => $this->location,
            'department' => $this->department,
            'job_type' => $this->job_type,
            'category_id' => $this->category_id,
            'status' => $this->status,
        ];
    }
}