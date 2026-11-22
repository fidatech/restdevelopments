<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSettingTranslation extends Model
{
    protected $fillable = ['about_setting_id','locale','shadow_title','title','subtitle','about_text','button_text','circle_text'];
    public function setting(){ return $this->belongsTo(AboutSetting::class,'about_setting_id'); }
}
