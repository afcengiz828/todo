<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "status" => $this->status,
            "priority" => $this->priority,
            "due_date" => $this->due_date,
            "categories" => new CategoryResource($this->whenLoaded('categories')),
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at
        ];
    }
}
