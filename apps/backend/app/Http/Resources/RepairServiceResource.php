<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairServiceResource extends JsonResource
{
    /**
     * Transformer le RepairService en réponse JSON pour l'API
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'technician_id' => $this->technician_id,
            'client_id' => $this->client_id,
            'listing_id' => $this->listing_id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'estimated_days' => $this->estimated_days,
            'status' => $this->status,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'accepted_at' => $this->accepted_at?->toIso8601String(),
            'started_at' => $this->started_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            
            // Relations (chargées si présentes)
            'technician' => new UserResource($this->whenLoaded('technician')),
            'client' => new UserResource($this->whenLoaded('client')),
            'listing' => new ListingResource($this->whenLoaded('listing')),
            
            // Calculated fields
            'estimated_days_remaining' => $this->when($this->status === 'in_progress', $this->estimatedDaysRemaining()),
            'is_completed' => $this->isCompleted(),
        ];
    }
}