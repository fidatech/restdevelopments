<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FounderSettingTranslation extends Model
{
    protected $fillable = ['founder_setting_id', 'locale', 'name', 'position', 'quote', 'message', 'biography'];

    public function setting()
    {
        return $this->belongsTo(FounderSetting::class, 'founder_setting_id');
    }
}
