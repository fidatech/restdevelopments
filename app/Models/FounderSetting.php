<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FounderSetting extends Model
{
    protected $fillable = ['status', 'image', 'signature'];
    protected $casts = ['status' => 'boolean'];

    public function translations()
    {
        return $this->hasMany(FounderSettingTranslation::class);
    }

    public function t($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?: $this->translations->firstWhere('locale', config('app.fallback_locale', 'en'));
    }
}
