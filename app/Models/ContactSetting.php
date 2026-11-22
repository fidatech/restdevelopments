<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'email_1','email_2','phone_1','phone_2',
        'facebook','twitter','linkedin','instagram','youtube',
        'logo_dark','logo_white','map_iframe'
    ];

    public function translations()
    {
        return $this->hasMany(ContactSettingTranslation::class);
    }

    public function t($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations
            ->where('locale',$locale)->first()
            ?: $this->translations->where('locale',config('app.fallback_locale'))->first();
    }
}
