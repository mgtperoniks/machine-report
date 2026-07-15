<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterMachineCategory extends Model
{
    protected $table = 'master_machine_categories';

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
