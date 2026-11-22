<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFeature extends Model
{
    protected $fillable = ['project_id','type','icon_set','icon','image','order'];
  public function project(){ return $this->belongsTo(Project::class); }
  public function translations(){ return $this->hasMany(ProjectFeatureTranslation::class); }
  public function t($loc=null){
    $loc ??= app()->getLocale();
    return $this->translations->firstWhere('locale',$loc)
        ?? $this->translations->firstWhere('locale', config('app.fallback_locale','en'));
  }
}
