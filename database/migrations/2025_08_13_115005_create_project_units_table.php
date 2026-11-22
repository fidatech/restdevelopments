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
        Schema::create('project_units', function (Blueprint $t) {
            $t->id();
            $t->foreignId('project_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('bedrooms'); // e.g. 1,2,3,4,5
            $t->unsignedInteger('min_area')->nullable(); // sqft
            $t->unsignedInteger('max_area')->nullable(); // sqft
            $t->unsignedInteger('count')->nullable();    // optional quantity
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_units');
    }
};
