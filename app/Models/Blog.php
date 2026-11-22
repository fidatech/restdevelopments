<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'slug',
        'thumbnail',
        'thumb_small',
        'thumb_large',
        'status',
        'order',
    ];

    public function translations()
    {
        return $this->hasMany(BlogTranslation::class);
    }

    public function t(?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations->where('locale', $locale)->first()
            ?: $this->translations->where('locale', config('app.fallback_locale'))->first();
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
}
