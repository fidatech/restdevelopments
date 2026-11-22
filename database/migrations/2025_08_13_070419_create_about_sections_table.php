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
        Schema::create('about_sections', function (Blueprint $t) {
            $t->id();
            $t->string('type')->default('custom'); // 'vision','mission','values','custom'
            $t->boolean('status')->default(true);
            $t->unsignedInteger('order')->default(0);
            $t->enum('icon_set', ['feather','bi','image'])->default('feather');
            $t->string('icon')->nullable();     // feather name or bi class
            $t->string('image')->nullable();    // if icon_set=image
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_sections');
    }
};
