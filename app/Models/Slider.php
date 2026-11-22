<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model {
    protected $fillable = ['status','sort','video_url','image','image_mobile'];
    public function translations(){ return $this->hasMany(SliderTranslation::class); }
    public function translation($loc = null){
        $loc = $loc ?: app()->getLocale();
        return $this->translations->firstWhere('locale', $loc) ?? $this->translations->firstWhere('locale', 'en');
    }
    public function scopeOrdered($q){ return $q->orderBy('sort')->orderByDesc('id'); }
}

