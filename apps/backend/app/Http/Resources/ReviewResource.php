<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transformer le Review en réponse JSON pour l'API
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reviewer_id' => $this->reviewer_id,
            'reviewee_id' => $this->reviewee_id,
            'listing_id' => $this->listing_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'transaction_type' => $this->transaction_type,
            'is_verified' => $this->is_verified,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            
            // Relations (chargées si présentes)
            'reviewer' => new UserResource($this->whenLoaded('reviewer')),
            'reviewee' => new UserResource($this->whenLoaded('reviewee')),
            'listing' => new ListingResource($this->whenLoaded('listing')),
        ];
    }
}