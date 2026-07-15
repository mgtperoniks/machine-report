<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDepartment extends Model
{
    protected $table = 'master_departments';

    protected $fillable = [
        'code',
        'name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
