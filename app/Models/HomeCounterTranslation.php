<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCounterTranslation extends Model
{
    protected $fillable = ['home_counter_id','locale','title'];

    public function counter(){ return $this->belongsTo(HomeCounter::class,'home_counter_id'); }
}
