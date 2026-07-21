<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('machine_required_spareparts', function (Blueprint $table) {
            $table->unique(['machine_id', 'warehouse_item_code'], 'machine_sparepart_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('machine_required_spareparts', function (Blueprint $table) {
            $table->dropUnique('machine_sparepart_unique');
        });
    }
};
