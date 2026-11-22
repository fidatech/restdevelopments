<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyChooseUsSetting extends Model
{
    protected $fillable = [];

    public function translations()
    {
        return $this->hasMany(WhyChooseUsSettingTranslation::class);
    }

    /**
     * Helper like t() to get the translation for current locale (or fallback).
     */
    public function t(?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations->where('locale', $locale)->first()
            ?: $this->translations->where('locale', config('app.fallback_locale'))->first();
    }
}
