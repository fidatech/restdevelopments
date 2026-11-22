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
        Schema::create('home_counter_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('home_counter_id')->constrained()->cascadeOnDelete();
            $t->string('locale', 5);               // en, fa, ps
            $t->string('title');                   // e.g. "Happy Clients"
            $t->unique(['home_counter_id','locale']);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_counter_translations');
    }
};
