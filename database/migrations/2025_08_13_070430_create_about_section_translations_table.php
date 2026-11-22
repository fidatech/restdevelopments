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
        Schema::create('about_section_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('about_section_id')->constrained()->cascadeOnDelete();
            $t->string('locale', 5);
            $t->string('title')->nullable();
            $t->text('body')->nullable();
            $t->unique(['about_section_id','locale']);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_section_translations');
    }
};
