<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceExecutionAnswer extends Model
{
    protected $fillable = [
        'execution_id',
        'checklist_item_id',
        'score',
        'remarks',
    ];

    public function execution(): BelongsTo
    {
        return $this->belongsTo(MaintenanceExecution::class, 'execution_id');
    }

    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTemplateChecklist::class, 'checklist_item_id');
    }
}
