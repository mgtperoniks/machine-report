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
        Schema::table('machines', function (Blueprint $table) {
            $table->string('production_area')->nullable()->change();
            $table->string('criticality')->nullable()->change();
            $table->string('operational_status')->nullable()->change();
            $table->string('manufacturer')->nullable()->change();
            $table->string('model')->nullable()->change();
            $table->string('serial_number')->nullable()->change();
            $table->date('installation_date')->nullable()->change();
            $table->date('commissioning_date')->nullable()->change();
            $table->string('vendor')->nullable()->change();
            
            // Add new columns
            $table->boolean('is_active')->default(true);
            $table->string('lifecycle_status')->default('ACTIVE'); // ACTIVE, INACTIVE, RETIRED
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['is_active', 'lifecycle_status', 'notes', 'created_by']);
            
            $table->string('production_area')->change();
            $table->string('criticality')->change();
            $table->string('operational_status')->change();
            $table->string('manufacturer')->change();
            $table->string('model')->change();
            $table->string('serial_number')->change();
            $table->date('installation_date')->change();
            $table->date('commissioning_date')->change();
            $table->string('vendor')->change();
        });
    }
};
