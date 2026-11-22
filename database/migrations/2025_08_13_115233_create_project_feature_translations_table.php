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
        Schema::create('project_feature_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('project_feature_id')->constrained()->cascadeOnDelete();
            $t->string('locale', 8)->index();
            $t->string('title'); // e.g. "Community Pool"
            $t->unique(['project_feature_id', 'locale']);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_feature_translations');
    }
};
