<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyChooseUsSettingTranslation extends Model
{
    protected $fillable = [
        'why_choose_us_setting_id',
        'locale',
        'title',
        'description',
    ];
}
