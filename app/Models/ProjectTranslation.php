<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTranslation extends Model
{
    protected $fillable = ['locale','title','description'];

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
