<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Le token d'authentification (n'est pas dans le modèle User)
     */
    public $token = null;

    /**
     * Définir le token pour la ressource
     */
    public function withToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Transformer en réponse JSON pour l'API
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'avatar_url' => $this->avatar_url,
                'bio' => $this->bio,
                'user_type' => $this->user_type,
                'is_verified' => $this->is_verified,
                'is_active' => $this->is_active,
                'created_at' => $this->created_at?->toIso8601String(),
            ],
            'token' => $this->token,
            'token_type' => 'Bearer',
        ];
    }
}