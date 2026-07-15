<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'machine_category',
        'maintenance_type',
        'estimated_duration',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'estimated_duration' => 'integer',
    ];

    /**
     * Get checklists associated with this maintenance template/package.
     */
    public function checklists(): HasMany
    {
        return $this->hasMany(MaintenanceTemplateChecklist::class, 'maintenance_template_id')->orderBy('sequence');
    }

    /**
     * Get required spareparts associated with this maintenance template/package.
     */
    public function spareparts(): HasMany
    {
        return $this->hasMany(MaintenanceTemplateSparepart::class, 'maintenance_template_id');
    }
}
