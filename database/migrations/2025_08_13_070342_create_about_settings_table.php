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
        Schema::create('about_settings', function (Blueprint $t) {
            $t->id();
            $t->boolean('status')->default(true);
            $t->string('button_url')->nullable();     // "Learn More" link
            $t->string('image_left')->nullable();     // home left image
            $t->string('image_right')->nullable();    // home right image
            $t->string('tag_image')->nullable();      // small tag/thumb image
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_settings');
    }
};
