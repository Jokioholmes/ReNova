<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RepairService extends Model
{
    use HasFactory;

    /**
     * Les attributs assignables en masse.
     */
    protected $fillable = [
        'technician_id',
        'client_id',
        'listing_id',
        'title',
        'description',
        'price',
        'estimated_days',
        'status',
        'location',
        'latitude',
        'longitude',
        'accepted_at',
        'started_at',
        'completed_at',
    ];

    /**
     * Les attributs castés.
     */
    protected $casts = [
        'price' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'estimated_days' => 'integer',
        'accepted_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation : Technicien qui offre le service
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Relation : Client qui demande le service
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Relation : Listing associé (optionnel)
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Scope : Services en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope : Services actifs (acceptés ou en cours)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['accepted', 'in_progress']);
    }

    /**
     * Scope : Services complétés
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope : Services d'un technicien
     */
    public function scopeByTechnician($query, int $technicianId)
    {
        return $query->where('technician_id', $technicianId);
    }

    /**
     * Accepter le service
     */
    public function accept(): void
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }

    /**
     * Commencer la réparation
     */
    public function start(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    /**
     * Marquer comme complété
     */
    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Annuler le service
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Vérifier si le service est complété
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Jours restants estimés
     */
    public function estimatedDaysRemaining(): ?int
    {
        if (!$this->started_at || !$this->estimated_days) {
            return null;
        }

        $daysElapsed = now()->diffInDays($this->started_at);
        return max(0, $this->estimated_days - $daysElapsed);
    }
}