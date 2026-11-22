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
        Schema::create('project_amenities', function (Blueprint $t) {
            $t->id();
            $t->string('image')->nullable();              // e.g. aminities1-1.png
            $t->string('icon')->nullable();               // e.g. aminities-icon1-1.svg
            $t->unsignedInteger('order')->default(0);
            $t->boolean('status')->default(true);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_amenities');
    }
};
