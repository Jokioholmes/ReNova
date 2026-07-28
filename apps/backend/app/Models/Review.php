<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    /**
     * Les attributs assignables en masse.
     */
    protected $fillable = [
        'reviewer_id',
        'reviewee_id',
        'listing_id',
        'rating',
        'comment',
        'transaction_type',
        'is_verified',
    ];

    /**
     * Les attributs castés.
     */
    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation : Utilisateur qui donne l'avis
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Relation : Utilisateur qui reçoit l'avis
     */
    public function reviewee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    /**
     * Relation : Listing associé
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Scope : Avis vérifiés uniquement
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope : Avis pour un utilisateur spécifique
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('reviewee_id', $userId);
    }

    /**
     * Scope : Avis positifs (rating >= 4)
     */
    public function scopePositive($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Scope : Avis négatifs (rating <= 2)
     */
    public function scopeNegative($query)
    {
        return $query->where('rating', '<=', 2);
    }

    /**
     * Marquer comme vérifié
     */
    public function verify(): void
    {
        $this->update(['is_verified' => true]);
    }

    /**
     * Obtenir le rating en étoiles (texte)
     */
    public function ratingText(): string
    {
        return match ($this->rating) {
            5 => '⭐⭐⭐⭐⭐ Excellent',
            4 => '⭐⭐⭐⭐ Très bon',
            3 => '⭐⭐⭐ Bon',
            2 => '⭐⭐ Passable',
            1 => '⭐ Mauvais',
            default => 'Aucun rating',
        };
    }
}