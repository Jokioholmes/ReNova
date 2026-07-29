<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs qui sont assignables en masse.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar_url',
        'bio',
        'user_type', // 'particulier', 'boutique', 'revendeur', 'reparateur', 'technicien'
        'is_verified',
        'is_active',
    ];

    /**
     * Les attributs qui doivent être cachés pour les sérialisations.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONS =====

    /**
     * Relation : Listings créés par cet utilisateur
     */
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * Relation : Avis donnés par cet utilisateur
     */
    public function givenReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    /**
     * Relation : Avis reçus par cet utilisateur
     */
    public function receivedReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    /**
     * Relation : Services de réparation offerts (si technicien)
     */
    public function repairServices(): HasMany
    {
        return $this->hasMany(RepairService::class, 'technician_id');
    }

    /**
     * Relation : Services de réparation demandés (comme client)
     */
    public function clientRepairServices(): HasMany
    {
        return $this->hasMany(RepairService::class, 'client_id');
    }

    // ===== SCOPES =====

    /**
     * Scope : Filtrer les utilisateurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope : Filtrer par type d'utilisateur
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('user_type', $type);
    }

    // ===== HELPERS =====

    /**
     * Vérifier si l'utilisateur est vendeur
     */
    public function isSeller(): bool
    {
        return in_array($this->user_type, ['boutique', 'revendeur']);
    }

    /**
     * Vérifier si l'utilisateur est technicien
     */
    public function isTechnician(): bool
    {
        return $this->user_type === 'technicien';
    }

    /**
     * Vérifier si l'utilisateur est particulier
     */
    public function isIndividual(): bool
    {
        return $this->user_type === 'particulier';
    }

    /**
     * Obtenir le rating moyen reçu
     */
    public function averageRating(): float
    {
        return $this->receivedReviews()->avg('rating') ?? 0;
    }

    /**
     * Nombre total d'avis reçus
     */
    public function reviewCount(): int
    {
        return $this->receivedReviews()->count();
    }

    /**
     * Nombre de listings actifs
     */
    public function activeListingsCount(): int
    {
        return $this->listings()->where('status', 'active')->count();
    }
}