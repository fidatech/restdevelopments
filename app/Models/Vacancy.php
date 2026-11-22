<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'deadline',
        'salary',
        'status',
        'slug',
    ];
    protected $casts = [
        'deadline' => 'date',
    ];


    public function translations()
    {
        return $this->hasMany(VacancyTranslation::class);
    }

    public function translation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();

        // Fallback to English if no record for current locale
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
    }
}
