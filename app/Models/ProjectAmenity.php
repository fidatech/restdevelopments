<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAmenity extends Model
{
    protected $fillable = ['image','icon','order','status'];

    public function translations()
    {
        return $this->hasMany(ProjectAmenityTranslation::class);
    }

    public function t($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?: $this->translations->firstWhere('locale', config('app.fallback_locale'));
    }

    // scopes
    public function scopeActive($q)  { $q->where('status', true); }
    public function scopeOrdered($q){ $q->orderBy('order'); }
}
