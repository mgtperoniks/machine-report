<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends Model
{
    protected $fillable = [
        'code',
        'name',
        'department',
        'production_area',
        'category',
        'criticality',
        'operational_status',
        'manufacturer',
        'model',
        'serial_number',
        'installation_date',
        'commissioning_date',
        'vendor',
        'qr_code_path',
    ];

    protected function casts(): array
    {
        return [
            'installation_date' => 'date',
            'commissioning_date' => 'date',
        ];
    }

    /**
     * Get structural component groups.
     */
    public function components(): HasMany
    {
        return $this->hasMany(MachineComponent::class);
    }

    /**
     * Get mapped warehouse spareparts.
     */
    public function requiredSpareparts(): HasMany
    {
        return $this->hasMany(MachineRequiredSparepart::class);
    }

    /**
     * Get machine documents (e.g. electrical drawings, manuals).
     */
    public function documents(): HasMany
    {
        return $this->hasMany(MachineDocument::class);
    }

    /**
     * Get machine photos.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(MachinePhoto::class);
    }

    /**
     * Accessor for simulated health score.
     * Calculated dynamically (not persisted) to support future Reliability Engine.
     */
    public function getHealthScoreAttribute(): int
    {
        return match (strtoupper($this->code)) {
            'CNC-08' => 38,
            'CNC-04' => 42,
            'ARM-12' => 58,
            'PMP-08' => 61,
            'DRL-19' => 85,
            default => match ($this->operational_status) {
                'breakdown' => 35,
                'maintenance' => 60,
                'stopped' => 70,
                'idle' => 80,
                'running' => 95,
                default => 100,
            }
        };
    }
}
