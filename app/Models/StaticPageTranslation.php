<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaticPageTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'static_page_id',
        'locale',
        'title',
        'content',
    ];
}
