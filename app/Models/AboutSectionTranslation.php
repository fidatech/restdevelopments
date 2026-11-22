<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSectionTranslation extends Model
{
    protected $fillable = ['about_section_id','locale','title','body'];
    public function section(){ return $this->belongsTo(AboutSection::class,'about_section_id'); }
}
