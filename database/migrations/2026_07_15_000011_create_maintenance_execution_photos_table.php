<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_execution_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_id')->constrained('maintenance_executions')->onDelete('cascade');
            $table->string('type')->default('general'); // general, reference, before, after
            $table->string('photo_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_execution_photos');
    }
};
