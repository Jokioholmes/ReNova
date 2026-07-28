<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    /**
     * Les attributs assignables en masse.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'images',
        'condition',
        'device_type',
        'brand',
        'model',
        'status',
        'location',
        'latitude',
        'longitude',
        'views',
        'published_at',
    ];

    /**
     * Les attributs castés.
     */
    protected $casts = [
        'images' => 'array',
        'price' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'views' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation : Propriétaire du listing
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Avis sur ce listing
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relation : Services de réparation associés
     */
    public function repairServices(): HasMany
    {
        return $this->hasMany(RepairService::class);
    }

    /**
     * Scope : Listings actifs uniquement
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->whereNotNull('published_at');
    }

    /**
     * Scope : Filtrer par condition
     */
    public function scopeOfCondition($query, string $condition)
    {
        return $query->where('condition', $condition);
    }

    /**
     * Scope : Filtrer par type d'appareil
     */
    public function scopeOfDeviceType($query, string $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    /**
     * Scope : Filtrer par prix (min-max)
     */
    public function scopePriceRange($query, float $min, float $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope : Recherche full-text
     */
    public function scopeSearch($query, string $term)
    {
        return $query->whereRaw("to_tsvector('french', title || ' ' || description) @@ plainto_tsquery('french', ?)", [$term]);
    }

    /**
     * Publier le listing
     */
    public function publish(): void
    {
        $this->update([
            'status' => 'active',
            'published_at' => now(),
        ]);
    }

    /**
     * Marquer comme vendu
     */
    public function markAsSold(): void
    {
        $this->update(['status' => 'sold']);
    }

    /**
     * Archiver le listing
     */
    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }

    /**
     * Incrémenter les vues
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Rating moyen du listing
     */
    public function averageRating(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Nombre d'avis
     */
    public function reviewCount(): int
    {
        return $this->reviews()->count();
    }
}