<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyChooseUsItem extends Model
{
    protected $fillable = [
        'icon',
        'image',
        'order',
    ];

    public function translations()
    {
        return $this->hasMany(WhyChooseUsItemTranslation::class);
    }

    public function t(?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations->where('locale', $locale)->first()
            ?: $this->translations->where('locale', config('app.fallback_locale'))->first();
    }
}
