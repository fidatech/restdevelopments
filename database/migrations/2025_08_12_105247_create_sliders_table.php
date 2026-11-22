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
        Schema::create('sliders', function (Blueprint $t) {
            $t->id();
            $t->string('image')->nullable();        // storage path (cover/desktop)
            $t->string('image_mobile')->nullable(); // optional mobile image
            $t->string('video_url')->nullable();    // optional
            $t->string('status')->default('published'); // published|draft
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
