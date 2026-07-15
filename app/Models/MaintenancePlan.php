<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenancePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'maintenance_template_id',
        'scheduled_date',
        'assigned_technician',
        'priority',
        'status',
        'generation_source',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    /**
     * Get the machine scheduled for maintenance.
     */
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    /**
     * Get the maintenance template/package (SOP knowledge) for this plan.
     */
    public function maintenanceTemplate(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTemplate::class, 'maintenance_template_id');
    }

    /**
     * Alias for maintenanceTemplate.
     */
    public function pmTemplate(): BelongsTo
    {
        return $this->maintenanceTemplate();
    }

    /**
     * Get the execution record for this plan, if any.
     */
    public function execution()
    {
        return $this->hasOne(MaintenanceExecution::class, 'maintenance_plan_id');
    }
}
