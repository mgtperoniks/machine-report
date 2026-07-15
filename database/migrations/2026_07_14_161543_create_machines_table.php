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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->string('name');
            $table->string('department');
            $table->string('production_area');
            $table->string('category');
            $table->string('criticality'); // low, medium, high, mission_critical
            $table->string('operational_status'); // running, idle, maintenance, breakdown, stopped
            $table->string('manufacturer');
            $table->string('model');
            $table->string('serial_number');
            $table->date('installation_date');
            $table->date('commissioning_date');
            $table->string('vendor');
            $table->string('qr_code_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
