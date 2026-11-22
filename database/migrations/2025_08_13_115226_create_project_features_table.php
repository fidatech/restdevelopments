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
        Schema::create('project_features', function (Blueprint $t) {
            $t->id();
            $t->foreignId('project_id')->constrained()->cascadeOnDelete();
            $t->enum('type', ['amenity', 'facility'])->default('amenity');
            $t->enum('icon_set', ['feather', 'bi', 'image'])->default('feather');
            $t->string('icon')->nullable();     // feather/bi class when not image
            $t->string('image')->nullable();    // small icon image (e.g. 200x200 webp)
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_features');
    }
};
