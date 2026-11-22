<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyChooseUsItemTranslation extends Model
{
    protected $fillable = [
        'why_choose_us_item_id',
        'locale',
        'title',
        'description',
    ];
}
