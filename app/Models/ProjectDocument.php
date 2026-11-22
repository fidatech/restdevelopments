<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    protected $fillable = ['project_id','type','title','file','preview_image','order'];
    public function project(){ return $this->belongsTo(Project::class); }
}
