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
        Schema::create('project_unit_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('project_unit_id')->constrained()->cascadeOnDelete();
            $t->string('locale', 8)->index();
            $t->string('name')->nullable();        // “2 Bedroom Townhouse”
            $t->text('description')->nullable();
            $t->unique(['project_unit_id', 'locale']);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_unit_translations');
    }
};
