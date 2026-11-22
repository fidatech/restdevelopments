<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUnitTranslation extends Model
{
    protected $fillable = ['project_unit_id','locale','name','description'];
    public function unit(){ return $this->belongsTo(ProjectUnit::class,'project_unit_id'); }
}
