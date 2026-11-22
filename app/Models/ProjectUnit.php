<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUnit extends Model
{
    protected $fillable = ['project_id','bedrooms','min_area','max_area','count','order'];

    public function project(){ return $this->belongsTo(Project::class); }
    public function translations(){ return $this->hasMany(ProjectUnitTranslation::class); }
}
