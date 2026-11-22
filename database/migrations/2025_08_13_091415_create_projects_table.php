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
        Schema::create('projects', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->enum('state', ['ongoing','completed'])->default('ongoing'); // business state
            $t->boolean('featured')->default(false);
            $t->boolean('status')->default(true);   // active/inactive toggle
            $t->unsignedInteger('order')->default(0);
            $t->string('image')->nullable();        // 800x440 webp
            $t->string('location')->nullable();
            $t->string('price_starts_from')->nullable();
            $t->unsignedSmallInteger('beds')->nullable();
            $t->unsignedSmallInteger('baths')->nullable();
            $t->unsignedInteger('area')->nullable(); // sqft
            $t->string('map_embed_url')->nullable();
            $t->decimal('lat',10,7)->nullable();
            $t->decimal('lng',10,7)->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
