<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    protected $fillable = ['status','button_url','image_left','image_right','tag_image'];
    protected $casts = ['status'=>'boolean'];

    public function translations(){ return $this->hasMany(AboutSettingTranslation::class); }

    public function t($locale=null){
        $locale = $locale ?: app()->getLocale();
        return $this->translations->firstWhere('locale',$locale)
            ?: $this->translations->firstWhere('locale', config('app.fallback_locale','en'));
    }
}
