<?php

namespace App\DTOs;

/**
 * RegisterUserDTO
 * 
 * Data Transfer Object pour l'inscription d'un utilisateur.
 * Type-safe et validé.
 */
class RegisterUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $user_type = 'particulier',
        public ?string $phone = null,
        public ?string $bio = null,
    ) {}

    /**
     * Créer un DTO à partir d'un tableau
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            user_type: $data['user_type'] ?? 'particulier',
            phone: $data['phone'] ?? null,
            bio: $data['bio'] ?? null,
        );
    }

    /**
     * Convertir le DTO en tableau pour la création du modèle
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'user_type' => $this->user_type,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'is_verified' => false,
            'is_active' => true,
        ];
    }
}