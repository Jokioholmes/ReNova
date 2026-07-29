<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
{
    /**
     * Transformer le Listing en réponse JSON pour l'API
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'images' => $this->images ?? [],
            'condition' => $this->condition,
            'device_type' => $this->device_type,
            'brand' => $this->brand,
            'model' => $this->model,
            'status' => $this->status,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'views' => $this->views,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            
            // Relations (chargées si présentes)
            'user' => new UserResource($this->whenLoaded('user')),
            'reviews_count' => $this->when($this->relationLoaded('reviews'), $this->reviews()->count()),
            'average_rating' => $this->when($this->relationLoaded('reviews'), $this->reviews()->avg('rating')),
        ];
    }
}