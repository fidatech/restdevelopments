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
        Schema::create('project_documents', function (Blueprint $t) {
            $t->id();
            $t->foreignId('project_id')->constrained()->cascadeOnDelete();
            $t->enum('type', ['master_plan', 'floor_plan', 'brochure'])->index();
            $t->string('title')->nullable();
            $t->string('file');            // pdf path
            $t->string('preview_image')->nullable(); // compressed webp (e.g. 1200x660)
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_documents');
    }
};
