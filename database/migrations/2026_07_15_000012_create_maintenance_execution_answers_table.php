<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_execution_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_id')->constrained('maintenance_executions')->onDelete('cascade');
            $table->foreignId('checklist_item_id')->constrained('maintenance_template_checklists')->onDelete('cascade');
            $table->integer('score'); // 1-5 score
            $table->text('remarks')->nullable(); // required if score is 1
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_execution_answers');
    }
};
