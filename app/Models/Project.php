<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'state',
        'status',
        'order',
        'image',
        'location',
        'beds',
        'baths',
        'area',
        'map_embed_url',
        'lat',
        'lng',
        'slug',
        'price_starts_from',
        'featured',
    ];

    public function translations()
    {
        return $this->hasMany(ProjectTranslation::class);
    }

    public function t(?string $locale = null)
    {
        $locale ??= app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', config('app.fallback_locale', 'en'));
    }


    // Scopes
    public function scopeFeatured($q)
    {
        return $q->where('featured', true)->where('status', true);
    }
    public function scopeActive($q)
    {
        return $q->where('status', true);
    }
    public function scopeOngoing($q)
    {
        return $q->where('state', 'ongoing');
    }

    public function amenities()
    {
        return $this->hasMany(\App\Models\ProjectAmenity::class)->orderBy('order');
    }

    public function features()
    {
        return $this->hasMany(\App\Models\ProjectFeature::class)->orderBy('order');
    }

    public function units()
    {
        return $this->hasMany(\App\Models\ProjectUnit::class)->orderBy('order');
    }

    public function galleries()
    {
        return $this->hasMany(\App\Models\ProjectGallery::class)->orderBy('order');
    }

    public function documents()
    {
        return $this->hasMany(\App\Models\ProjectDocument::class)->orderBy('order');
    }
}
