<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'phone',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'order',
    ];

    public function translations()
    {
        return $this->hasMany(TeamTranslation::class);
    }

    public function t(?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();

        return $this->translations->where('locale', $locale)->first()
            ?: $this->translations->where('locale', config('app.fallback_locale'))->first();
    }
}
