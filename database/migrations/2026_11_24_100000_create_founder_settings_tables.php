<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('founder_settings', function (Blueprint $t) {
            $t->id();
            $t->boolean('status')->default(true);
            $t->string('image')->nullable();      // portrait
            $t->string('signature')->nullable();  // optional signature image
            $t->timestamps();
        });

        Schema::create('founder_setting_translations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('founder_setting_id')->constrained()->cascadeOnDelete();
            $t->string('locale', 5);
            $t->string('name')->nullable();
            $t->string('position')->nullable();     // "Founder & Chairman"
            $t->text('quote')->nullable();          // short pull quote
            $t->longText('message')->nullable();    // the founder's message (HTML)
            $t->longText('biography')->nullable();  // about the founder (HTML)
            $t->unique(['founder_setting_id', 'locale']);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('founder_setting_translations');
        Schema::dropIfExists('founder_settings');
    }
};
