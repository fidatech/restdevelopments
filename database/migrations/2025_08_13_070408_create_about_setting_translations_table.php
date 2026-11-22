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
        Schema::create('about_setting_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('about_setting_id')->constrained()->cascadeOnDelete();
            $t->string('locale', 5);
            $t->string('shadow_title')->nullable();   // "About"
            $t->string('title')->nullable();          // "About Us"
            $t->text('subtitle')->nullable();         // short paragraph under title (home)
            $t->text('about_text')->nullable();       // long line under tag on home
            $t->string('button_text')->nullable();    // "Learn More"
            $t->string('circle_text')->nullable();    // rotating circle text
            $t->unique(['about_setting_id','locale']);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_setting_translations');
    }
};
