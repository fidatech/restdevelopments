<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SliderTranslation extends Model {
    protected $fillable = ['slider_id','locale','headline','subheadline','button_text','button_url'];
    public function slider(){ return $this->belongsTo(Slider::class); }
}

