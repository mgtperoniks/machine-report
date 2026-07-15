<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceExecutionPhoto extends Model
{
    protected $fillable = [
        'execution_id',
        'type',
        'photo_path',
    ];

    public function execution(): BelongsTo
    {
        return $this->belongsTo(MaintenanceExecution::class, 'execution_id');
    }
}
