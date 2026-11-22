<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAmenityTranslation extends Model
{
    protected $fillable = ['locale','title', 'project_amenity_id'];
    public function amenity(){ return $this->belongsTo(ProjectAmenity::class,'project_amenity_id'); }
}
