<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceTemplateSparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_template_id',
        'warehouse_item_code',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get parent template/package.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTemplate::class, 'maintenance_template_id');
    }
}
