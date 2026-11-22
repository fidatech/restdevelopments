<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFeatureTranslation extends Model
{
    protected $fillable = ['locale','title'];
  public function feature(){ return $this->belongsTo(ProjectFeature::class,'project_feature_id'); }
}
