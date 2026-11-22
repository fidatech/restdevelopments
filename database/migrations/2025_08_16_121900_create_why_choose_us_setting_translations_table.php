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
        Schema::create('why_choose_us_setting_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('why_choose_us_setting_id');
            // FK name shortened to avoid MySQL 64-char limit
            $table->foreign('why_choose_us_setting_id', 'wcu_setting_fk')
                  ->references('id')
                  ->on('why_choose_us_settings')
                  ->onDelete('cascade');

            $table->string('locale', 5);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['why_choose_us_setting_id', 'locale'], 'wcu_settings_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('why_choose_us_setting_translations');
    }
};
