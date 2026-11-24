<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFeatureTranslation extends Model
{
    protected $fillable = ['project_feature_id','locale','title','description'];
  public function feature(){ return $this->belongsTo(ProjectFeature::class,'project_feature_id'); }
}
