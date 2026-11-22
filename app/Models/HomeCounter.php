<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCounter extends Model
{
    protected $fillable = ['number','order','status'];
    protected $casts = ['status'=>'boolean','order'=>'integer','number'=>'integer'];

    public function translations(){ return $this->hasMany(HomeCounterTranslation::class); }

    public function scopeActive($q){ return $q->where('status',1); }

    public function t($locale = null){
        $locale = $locale ?: app()->getLocale();
        return $this->translations->firstWhere('locale',$locale)
            ?: $this->translations->firstWhere('locale','en');
    }
}
