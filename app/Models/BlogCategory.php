<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = ['status','order'];

    public function translations()
    {
        return $this->hasMany(BlogCategoryTranslation::class);
    }

    public function t(?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations->where('locale', $locale)->first()
            ?: $this->translations->where('locale', config('app.fallback_locale'))->first();
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
