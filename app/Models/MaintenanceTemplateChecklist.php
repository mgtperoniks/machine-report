<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceTemplateChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_template_id',
        'sequence',
        'title',
        'description',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'sequence' => 'integer',
    ];

    /**
     * Get parent template/package.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTemplate::class, 'maintenance_template_id');
    }
}
