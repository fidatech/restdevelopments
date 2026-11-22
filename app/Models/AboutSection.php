<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    protected $fillable = ['type','status','order','icon_set','icon','image'];
    protected $casts = ['status'=>'boolean','order'=>'integer'];

    public function translations(){ return $this->hasMany(AboutSectionTranslation::class); }

    public function scopeActive($q){ return $q->where('status',1); }

    public function t($locale=null){
        $locale = $locale ?: app()->getLocale();
        return $this->translations->firstWhere('locale',$locale)
            ?: $this->translations->firstWhere('locale', config('app.fallback_locale','en'));
    }
}
