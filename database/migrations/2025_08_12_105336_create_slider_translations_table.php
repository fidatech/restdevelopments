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
        Schema::create('slider_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('slider_id')->constrained()->cascadeOnDelete();
            $t->string('locale', 5); // en, ps, fa, uz
            $t->string('headline')->nullable();
            $t->string('subheadline')->nullable();
            $t->string('button_text')->nullable();
            $t->string('button_url')->nullable();
            $t->unique(['slider_id','locale']);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider_translations');
    }
};
